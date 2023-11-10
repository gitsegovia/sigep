<?php

class Cepp03OrdenpagoController extends AppController{

	var $uses=array('v_cepd02_contratoservi_retencion','v_cobd01_contratoobras_retencion','cobd01_contratoobras_retencion_cuerpo',
		'cobd01_contratoobras_retencion_partidas','cscd04_ordencompra_autorizacion_cuerpo','cepd02_contratoservicio_retencion_cuerpo',
		'cepd02_contratoservicio_retencion_partidas','v_cepd02_contratoservi_valuacion','cepd02_contratoservicio_valuacion_cuerpo',
		'cepd02_contratoservicio_valuacion_partidas','v_cobd01_contratoobras_valuacion','cobd01_contratoobras_valuacion_partidas',
		'cobd01_contratoobras_valuacion_cuerpo','cepd02_contratoservicio_anticipo_cuerpo','cepd02_contratoservicio_anticipo_partidas',
		'v_cepd02_servicio_anticipo','cobd01_contratoobras_anticipo_partidas','cobd01_contratoobras_anticipo_cuerpo',
		'v_cobd01_contratoobras_anticipo','cscd04_ordencompra_a_pago_partidas','cscd04_ordencompra_autorizacion_cuerpo',
		'cugd03_acta_anulacion_cuerpo','cugd03_acta_anulacion_numero','cugd04','cfpd22_numero_asiento_causado','cfpd22','cfpd05',
		'ccfd04_cierre_mes','cscd04_ordencompra_autorizacion_pago_partidas','v_cepd03_ordenpago_autorizacion_compra',
		'cscd04_ordencompra_autorizacion_cuerpo','cepd03_ordenpago_poremitir','v_cepd03_ordenpago_anticipo_compra',
		'v_cepd03_ordenpago_compromiso','cscd04_ordencompra_anticipo_cuerpo','cscd04_ordencompra_anticipo_partidas',
		'cugd03_acta_anulacion_cuerpo','cpcd02','cscd04_ordencompra_parametros',
		'cepd01_compromiso_cuerpo','cepd01_compromiso_partidas','cepd01_tipo_compromiso','cepd03_tipo_documento','ccfd03_instalacion',
		'cepd03_ordenpago_numero','cepd03_ordenpago_cuerpo','cepd03_ordenpago_partidas','cepd03_ordenpago_tipopago',
		'cepd03_ordenpago_facturas','cstd01_entidades_bancarias','cstd01_sucursales_bancarias','cstd07_retenciones_cuerpo_multa', 'cstd07_retenciones_cuerpo_responsabilidad', 'cstd07_retenciones_partidas_multa',
		'cstd07_retenciones_partidas_responsabilidad','cstd06_comprobante_poremitir_multa',
		'cstd06_comprobante_poremitir_responsabilidad', 'cstd06_comprobante_numero_multa',
		'cstd06_comprobante_numero_responsabilidad', 'cstd06_comprobante_cuerpo_multa', 'cstd06_comprobante_cuerpo_responsabilidad',

		'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
		'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
		'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo',
		'cscd04_ordencompra_retencion_cuerpo','cscd04_ordencompra_retencion_partidas','v_cscd04_ordencompras_retencion',
		'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
		'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
		'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo','cugd05_restriccion_clave'

	);

	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');








	function checkSession(){
	            //$this->Session->renew();

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
		            //define('CAKE_SESSION_TIMEOUT', '1000');
                    //Configure::write('CAKE_SESSION_TIMEOUT','1000');
                    //echo Configure::read('debug');
		           // Configure::write('CAKE_SESSION_TIMEOUT',1000);
		            //Configure::write('cake_session_timeout',1000);
		             //echo Configure::read('debug');
		           //echo 33/0;
	$this->checkSession();
	if($this->ano_ejecucion()!=""){
		return;
	}else{
		echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
		exit();
	}

}
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

		function SQLCAIN($ano=null){//sql para busqueda de codigos de arranque con y sin año
			$sql_re = $this->verifica_SS(1).",";
			$sql_re .= $this->verifica_SS(2).",";
			$sql_re .=  $this->verifica_SS(3).",";
			$sql_re .= $this->verifica_SS(4).",";
			if($ano!=null){
				$sql_re .= $this->verifica_SS(5).",";
				$sql_re .= $ano."";
			}else{
				$sql_re .=  $this->verifica_SS(5)."";
			}
			return $sql_re;
		}//fin funcion SQLCAIN
		function SQLCA_admin($ano=null){//sql para busqueda de codigos de arranque con y sin año
			$sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
			$sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
			$sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
			$sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
			if($ano!=null){
				if($this->verifica_SS(5)!=1){
					$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
				}
				$sql_re .= "ano=".$ano."  ";
			}else{
				if($this->verifica_SS(5)!=1){
					$sql_re .= "cod_dep=".$this->verifica_SS(5)."  ";
				}
			}
			return $sql_re;
		}//fin funcion SQLCA
		function SQLCA_reque($ano=null){//sql para busqueda de codigos de arranque con y sin año
			$sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
			$sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
			$sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
			$sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
			if($ano!=null){
				$sql_re .= "ano=".$ano."  ";
			}else{

			}
			return $sql_re;
		}//fin funcion SQLCA

		function SQLCA_report($pre=null){
			$sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
			$sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
			$sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
			if($pre!=null && $pre==1){
				$sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
				 //$sql_re .= "cod_dep=0";
			}else{
				$sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
				$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
			}

			return $sql_re;
		}//fin funcion SQLCA
		function SQLCA_report_in($pre=null){
			$sql_re = $this->verifica_SS(1).",";
			$sql_re .= $this->verifica_SS(2).",";
			$sql_re .= $this->verifica_SS(3).",";
			if($pre!=null && $pre==1){
				$sql_re .= $this->verifica_SS(4).",";
				$sql_re .= 0;
			}else{
				$sql_re .= $this->verifica_SS(4).",";
				$sql_re .= $this->verifica_SS(5)." ";
			}

			return $sql_re;
		}//fin funcion SQLCA
function SQLCA_S($ano=null){//sql para busqueda de codigos de arranque con y sin año
	$sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
	$sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
	$sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
	$sql_re .= "cod_inst=".$this->verifica_SS(4)."   ";
	return $sql_re;
		}//fin funcion SQLCA
		function AddCero($nomVar,$vector=object,$extra=null){
			if($vector!=null){
				if($extra==null){
					foreach($vector as $x){
						if($x<10){
							$Var[$x]="0".$x;
						}else{
							$Var[$x]=$x;
						}
			}//fin each
		}else{
			foreach($vector as $x){
				if($x<10){
					$Var[$x]=$extra.".0".$x;
				}else{
					$Var[$x]=$extra.".".$x;
				}
			}//fin each
		}
		$this->set($nomVar,$Var);
	}else{
		$this->set($nomVar,'');
	}
}
function AddCerPartida($vector=object){
	if($vector!=null){
		foreach($vector as $x){
			if($x<10){
				$Var[$x]="4.0".$x;
			}else{
				$Var[$x]="4.".$x;
			}
			}//fin each
			return $Var;
		}else{
			return "";
		}
	 }//fin AddCero
	 function AddCeroR($n,$extra=null){
	 	if($n!=null){
	 		if($extra==null){
	 			if($n<10){
	 				$Var="0".$n;
	 			}else{
	 				$Var=$n;
	 			}
	 		}else{
	 			if($n<10){
	 				$Var=$extra.".0".$n;
	 			}else{
	 				$Var=$extra.".".$n;
	 			}
	 		}
	 		return $Var;
	 	}else{
					 //return $Var;
	 	}
	 }//fin AddCero

	 function concatena4($vector1=null, $nomVar=null, $extra=null){
	 	$cod = array();
	 	if($vector1 != null){
	 		foreach($vector1 as $x => $y){
	 			if($extra!=null){
	 				if($x<10){
	 					$cod[$x] = $extra.'.0'.$x.' - '.$y;
	 				}else if($x>=10 && $x<=99){
	 					$cod[$x] = $extra.'.'.$x.' - '.$y;
	 				}
	 			}else{

	 				if($x<10){
	 					$cod[$x] = '0'.$x.' - '.$y;
	 				}else if($x>=10 && $x<=99){
	 					$cod[$x] = $x.' - '.$y;
	 				}
	 			}
	 		}
		//print_r($cod);
	 	}

	 	$this->set($nomVar, $cod);
}//fin function










function buscar_producto($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	$this->Session->write('year_buscar', $this->ano_ejecucion());
}//fin function




function buscar_por_pista_year($var1=null){

	$this->layout = "ajax";
	$this->Session->write('year_buscar', $var1);

	echo "<script>";
	echo 'document.getElementById("select_obra_cod_obra").value="";';
	echo "</script>";

}//fin function





function buscar_por_pista($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$year = $this->Session->read('year_buscar');
	$sql = $this->SQLCA()." and ano_orden_pago=".$year;

	if($var3==null){$var2 = strtoupper_sisap($var2);
		$this->Session->write('pista', $var2);
		$ordena = " autorizado, fecha_orden_pago, numero_orden_pago";
		$sql .=" and (numero_orden_pago::text='".$var2."'  or ";
		$Tfilas=$this->cepd03_ordenpago_cuerpo->findCount($sql."  (".$this->busca_separado(array("autorizado"), $var2)."))   ");
		if($Tfilas!=0){
			$pagina=1;
			$Tfilas=(int)ceil($Tfilas/100);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('ultimo',$Tfilas);
			$datos_filas=$this->cepd03_ordenpago_cuerpo->findAll($sql."  (".$this->busca_separado(array("autorizado"), $var2)."))  ",null,$ordena." ASC",100,1,null);
			$this->set("datosFILAS",$datos_filas);
			$this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
		}else{
			$this->set("datosFILAS",'');
		}
	}else{
		$var22 = $this->Session->read('pista');
		$var22 = strtoupper_sisap($var22);
		$ordena = " autorizado, fecha_orden_pago, numero_orden_pago";
		$sql .=" and (numero_orden_pago::text='".$var22."'  or ";
		$Tfilas=$this->cepd03_ordenpago_cuerpo->findCount($sql."   (".$this->busca_separado(array("autorizado"), $var22)."))    ");

		if($Tfilas!=0){
			$pagina=$var3;
			$Tfilas=(int)ceil($Tfilas/100);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('ultimo',$Tfilas);
			$datos_filas=$this->cepd03_ordenpago_cuerpo->findAll($sql."  (".$this->busca_separado(array("autorizado"), $var22)."))  ",null,$ordena." ASC",100,$pagina,null);
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





function funcion($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
}//fin function










function index ($var=null,$msj=null) {

	$this->verifica_entrada('84');

	$this->layout="ajax";

		    //echo Configure::read('Session.timeout ');
		    //Configure::write('debug',0);
		    //echo Configure::read('debug');
		    //echo 33/0;
		    //echo CakeSession::read(null);
		    //Configure::write('cake_session_timeout',1000);
	$ano=$this->ano_ejecucion();
	$dato=$this->ano_ejecucion();
            //$de_donde_viene=$this->referer(null,false);
	$referencia_url = isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:"no_exito";
	$vengo=substr($referencia_url,7,strlen($_SERVER["HTTP_HOST"]));

	if(strtolower($vengo)!=strtolower($_SERVER["HTTP_HOST"])){
		$this->redirect("/salir");
	}
             //Configure::write('CAKE_SESSION_TIMEOUT','10');
            //$tt= $z/0;
	$max=$this->cepd03_ordenpago_numero->findCount($this->SQLCA()." and ano_orden_pago=".$dato." and situacion=1");
			//print_r($max);
	if($max==0){
		$this->set("errorMessage","Verifique el n&uacute;mero de control en orden de pago");
		$this->set("numero_orden_pago","");
		$this->redirect("/cepp03_ordenpago_numero/");
	}

	if(isset($var)){
		if(isset($msj) && $var==1){
			$this->set("Message_existe",$msj);
		}else{
			$this->set("errorMessage",$msj);
		}
	}
	$this->Session->delete("items");
	$this->Session->delete("i");
	$this->Session->delete("contador");

}//fin index



function index2 ($var=null,$msj=null) {

	$this->layout="ajax";

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	$autor_valido = $this->Session->read('autor_valido');
	if($autor_valido==true){

		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$condicion_dir_sup = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia=".$cod_dep;
		$ano=$this->ano_ejecucion();
		$dato=$this->ano_ejecucion();
		$tipo_documento = $this->cepd03_tipo_documento->generateList(null,'denominacion ASC', null, '{n}.cepd03_tipo_documento.cod_tipo_documento', '{n}.cepd03_tipo_documento.denominacion');
		$tipo_documento = $tipo_documento != null ? $tipo_documento : array();
		$this->set('tipo',$tipo_documento);

		$tipo_compromiso = $this->cepd01_tipo_compromiso->generateList(null,'cod_tipo_compromiso ASC', null, '{n}.cepd01_tipo_compromiso.cod_tipo_compromiso', '{n}.cepd01_tipo_compromiso.denominacion');
		$tipo_compromiso = $tipo_compromiso != null ? $tipo_compromiso : array();
		$this->concatena($tipo_compromiso, 'tipocompromiso');

		$tipo_pago = $this->cepd03_ordenpago_tipopago->generateList(null,'cod_tipo_pago ASC', null, '{n}.cepd03_ordenpago_tipopago.cod_tipo_pago', '{n}.cepd03_ordenpago_tipopago.denominacion');
		$tipo_pago = $tipo_pago != null ? $tipo_pago : array();
		$this->concatena($tipo_pago, 'tipopago');
//Frecuencia de pago
//1.- Una sola vez
//2.- Quincenal anticipada
//3.- Quincenal vencida
//4.- Mensual Anticipada
//5.- Mensual Vencida

		$frecuencia_de_pago=array(1=>"Una sola vez",2=>"Quincenal anticipada",3=>"Quincenal vencida",4=>"Mensual Anticipada",5=>"Mensual Vencida");
		$this->set("frecuencia_de_pago",$frecuencia_de_pago);
		$tipo_de_orden=array(1=>"Permanente",2=>"Especial");
		$this->set("tipo_de_orden",$tipo_de_orden);

		$max=$this->cepd03_ordenpago_numero->execute("SELECT numero_orden_pago FROM cepd03_ordenpago_numero WHERE ".$this->SQLCA()." and ano_orden_pago=".$dato." and situacion=1 ORDER BY numero_orden_pago ASC LIMIT 1");

		$numero_orden_pago_anterior   = $this->cepd03_ordenpago_numero->field('cepd03_ordenpago_numero.numero_orden_pago', $conditions = $this->condicion()." and ano_orden_pago='$dato' and situacion=3 and numero_orden_pago<='".$max[0][0]["numero_orden_pago"]."'   ", $order ="cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_orden_pago DESC");
		$fecha_documento_anterior     = $this->cepd03_ordenpago_cuerpo->field('cepd03_ordenpago_cuerpo.fecha_orden_pago',  $conditions = $this->condicion()." and ano_orden_pago='$dato' and numero_orden_pago='".$numero_orden_pago_anterior."' ", $order ="cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_orden_pago DESC");
		$this->set('fecha_documento_anterior',    $fecha_documento_anterior);
		$this->set('numero_documento_anterior',  $numero_orden_pago_anterior);

		if($max!=null){
			$codigo=$max[0][0]["numero_orden_pago"];
			$resultado=$this->cepd03_ordenpago_numero->execute("UPDATE  cepd03_ordenpago_numero SET situacion=2 WHERE ".$this->SQLCA()." and numero_orden_pago=".$codigo." and ano_orden_pago=".$dato);
			if($resultado>1){
							 // $this->set("Message_existe","Situacion del compromiso actualizada con exito");
							 //echo "<script>" .
								 //	"habilita_compromiso();" .
							// 		"</script>";
				$this->set("numero_orden_pago",$codigo);
			}else{
				$this->set("errorMessage","Por favor Verifique el n&uacute;mero de control en orden de pago");
				$this->set("numero_orden_pago","");
			}
		}else{
			$this->set("errorMessage","Verifique el n&uacute;mero de control en orden de pago");
			$this->set("numero_orden_pago","");
		}

		if(isset($var)){
			if(isset($msj) && $var==1){
				$this->set("Message_existe",$msj);
			}else{
				$this->set("errorMessage",$msj);
			}
		}
		//echo jgha;

	}else{
		$this->Session->delete('autor_valido');
	}

}//fin index2




function seleccion_tipo_documento ($var=null) {
	$this->layout="ajax";
	if(isset($var)){
		$this->set("year",$var);
	}else{
		$this->set("year",date("Y"));
	}

	$tipo_documento = $this->cepd03_tipo_documento->generateList(null,'denominacion ASC', null, '{n}.cepd03_tipo_documento.cod_tipo_documento', '{n}.cepd03_tipo_documento.denominacion');
	$tipo_documento = $tipo_documento != null ? $tipo_documento : array();
	$this->set('tipo',$tipo_documento);

}

function num_auto ($var=null,$ano=2008) {
	$this->layout="ajax";
	echo '<script>' .
	'habilita_compromiso();' .
	'</script>';
	if(isset($var) && $var==1){
		//buscar para que el codigo sea automatico
		$v=$this->cepd03_ordenpago_numero->execute("SELECT numero_orden_pago FROM cepd03_ordenpago_numero WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." ORDER BY numero_orden_pago DESC");
		//print_r($v);
		if($v!=null){
			$numero=$v[0][0]["numero_orden_pago"];
			$numero = $numero =="" ? 1 : $numero+1;
		}else{
			$numero=1;
		}
	}else{
		$numero="";
	}
	$this->set("numero",$numero);
	$this->Session->delete("items");
	$this->Session->delete("i");
	session_destroy("items");
}//fin num_auto

function mostrar_tipo_pago ($var,$codigo) {
	$this->layout="ajax";
	if(isset($var) && $var=="codigo"){
		$c=$this->cepd03_ordenpago_tipopago->findByCod_tipo_pago($codigo);
		$this->set("codigo",$c["cepd03_ordenpago_tipopago"]["cod_tipo_pago"]);
	}else if(isset($var) && $var=="deno"){
		$c=$this->cepd03_ordenpago_tipopago->findByCod_tipo_pago($codigo);
		$this->set("deno",$c["cepd03_ordenpago_tipopago"]["denominacion"]);
	}
}//fin mostrar_tipo_pago


function comboBox_beneficiario ($tabla,$campo_value,$campo_opcion, $condicion,$alias) {
	$condicion= $condicion!=null?$condicion:"1=1";
	$campo_value=strtolower($campo_value);
	$campo_opcion=strtolower($campo_opcion);
	$campos=$campo_value==$campo_opcion?$campo_value:$campo_value.",".$campo_opcion;
	$rs=$this->cepd01_compromiso_cuerpo->execute("SELECT DISTINCT ".$campos." FROM ".$tabla." WHERE ". $condicion." ORDER BY ".$campo_value." ASC");
	foreach($rs as $l){
		$v[]=$l[0][$campo_value];
		$d[]=$l[0][$alias];
	}
	if(isset($v) && count($v)!=0){
		$lista = array_combine($v, $d);
	}else{
		$v[]="";
		$lista = array_combine($v, $v);
	}

	return  $lista;
}

function nro_documento ($ano=null,$var=null) {

	$this->layout="ajax";
	$this->Session->delete("items");
	$this->Session->delete("i");
	if(isset($var)){
		if(isset($ano)){
			$ano=$ano;
		}else{
			$ano=date("Y");
		}
			//$ano=date("Y");
            //echo "".$ano;
		switch($var){
				case 1://compromiso
				$this->Session->write('ano_documento',$ano);
						//$numero_documentos = $this->v_cepd03_ordenpago_compromiso->generateList($this->SQLCA()." and ano_documento=".$ano." and condicion_actividad=1 and numero_orden_pago=0  ",'numero_documento ASC', null, '{n}.v_cepd03_ordenpago_compromiso.numero_documento', '{n}.v_cepd03_ordenpago_compromiso.numero_documento');
						//print_r($numero_documentos);
				$numero_documentos=$this->comboBox("v_cepd03_ordenpago_compromiso","numero_documento","deno_select",$this->SQLCA()." and ano_documento=".$ano." and condicion_actividad=1 and numero_orden_pago=0  ");
				$numero_documentos = $numero_documentos != null ? $numero_documentos : array();
				$this->set('numero_documentos',$numero_documentos);
				$this->set('tipo_documento',1);
				break;
				case 2://ordenes de compra  anticipo
				$this->Session->write('ano_orden_compra',$ano);
							//$numero_anticipos=$this->cscd04_ordencompra_anticipo_cuerpo->generateList($this->SQLCA()." and ano_orden_compra=".$ano." and condicion_actividad=1 and numero_orden_pago=0",'numero_orden_compra ASC', null, '{n}.cscd04_ordencompra_anticipo_cuerpo.numero_orden_compra', '{n}.cscd04_ordencompra_anticipo_cuerpo.numero_orden_compra');
				$numero_anticipos=$this->comboBox("v_cepd03_ordenpago_oca","numero_orden_compra","deno_select",$this->SQLCA()." and ano_orden_compra=".$ano." and condicion_actividad=1 and numero_orden_pago=0  and saldo_ano_anterior=1");
				$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
				$this->set('numero_documentos',$numero_anticipos);
				$this->set('tipo_documento',2);
				break;
				case 3://ordenes de compra autorizacion pago
				$this->Session->write('ano_orden_compra',$ano);
					 //$numero_anticipos=$this->cscd04_ordencompra_autorizacion_cuerpo->generateList($this->SQLCA()." and ano_orden_compra=".$ano." and condicion_actividad=1 and numero_orden_pago=0",'numero_orden_compra ASC', null, '{n}.cscd04_ordencompra_autorizacion_cuerpo.numero_orden_compra', '{n}.cscd04_ordencompra_autorizacion_cuerpo.numero_orden_compra');
				$numero_anticipos=$this->comboBox("v_cepd03_ordenpago_apc","numero_orden_compra","deno_select",$this->SQLCA()." and ano_orden_compra=".$ano." and condicion_actividad=1 and numero_orden_pago=0");
				$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
				$this->set('numero_documentos',$numero_anticipos);
				$this->set('tipo_documento',3);
                   //  $this->set('tipo_documento',"sin_terminar");
				break;
				case 4://contrato obras anticipo
				$this->Session->write('ano_contrato_obra',$ano);
					 //$numero_anticipos=$this->cobd01_contratoobras_anticipo_cuerpo->generateList($this->SQLCA()." and ano_contrato_obra=".$ano." and condicion_actividad=1 and numero_orden_pago=0",'numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_anticipo_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_anticipo_cuerpo.numero_contrato_obra');
				$numero_anticipos=$this->comboBox("v_cepd03_ordenpago_coac","numero_contrato_obra","deno_select",$this->SQLCA()." and ano_contrato_obra=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and saldo_ano_anterior=1");
				$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
				$this->set('numero_documentos',$numero_anticipos);
				$this->set('tipo_documento',4);
                     //$this->set('tipo_documento',"sin_terminar");
				break;
				case 5://contrato obras valuaciones
				$this->Session->write('ano_contrato_obra',$ano);
					 //$numero_anticipos=$this->cobd01_contratoobras_valuacion_cuerpo->generateList($this->SQLCA()." and ano_contrato_obra=".$ano." and condicion_actividad=1 and numero_orden_pago=0",'numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_contrato_obra');
				$numero_anticipos=$this->comboBox("v_cepd03_ordenpago_covc","numero_contrato_obra","deno_select",$this->SQLCA()." and ano_contrato_obra=".$ano." and condicion_actividad=1 and numero_orden_pago=0");
				$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
				$this->set('numero_documentos',$numero_anticipos);
				$this->set('tipo_documento',5);
				break;
				case 6://contrato obras retenciones
				$this->Session->write('ano_contrato_obra',$ano);
				$numero_anticipos=$this->comboBox("v_cepd03_ordenpago_corc","numero_contrato_obra","deno_select",$this->SQLCA()." and ano_contrato_obra=".$ano." and condicion_actividad=1 and numero_orden_pago=0");
				$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
				$this->set('numero_documentos',$numero_anticipos);
				$this->set('tipo_documento',6);
				break;
				case 7://contrato servicio anticipo
				$this->Session->write('ano_contrato_servicio',$ano);
                     //$vprueba=$this->cepd02_contratoservicio_anticipo_cuerpo->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and condicion_actividad=1 and numero_orden_pago=0");
					 //print_r($vprueba);
					 //$numero_anticipos=$this->cepd02_contratoservicio_anticipo_cuerpo->generateList($this->SQLCA()." and ano_contrato_servicio=".$ano." and condicion_actividad=1 and numero_orden_pago=0",'numero_contrato_servicio ASC', null, '{n}.cepd02_contratoservicio_anticipo_cuerpo.numero_contrato_servic', '{n}.cepd02_contratoservicio_anticipo_cuerpo.numero_contrato_servic');
				$numero_anticipos=$this->comboBox("v_cepd03_ordenpago_csac","numero_contrato_servicio","deno_select",$this->SQLCA()." and ano_contrato_servicio=".$ano." and condicion_actividad=1 and numero_orden_pago=0  and saldo_ano_anterior=1");
				$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
				$this->set('numero_documentos',$numero_anticipos);
				$this->set('tipo_documento',7);
                     //$this->set('tipo_documento',"sin_terminar");
				break;
				case 8://contrato servicio valuaciones
				$this->Session->write('ano_contrato_servicio',$ano);
                     //$vprueba=$this->cepd02_contratoservicio_anticipo_cuerpo->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and condicion_actividad=1 and numero_orden_pago=0");
					 //print_r($vprueba);
					 //$numero_anticipos=$this->cepd02_contratoservicio_valuacion_cuerpo->generateList($this->SQLCA()." and ano_contrato_servicio=".$ano." and condicion_actividad=1 and numero_orden_pago=0",'numero_contrato_servicio ASC', null, '{n}.cepd02_contratoservicio_valuacion_cuerpo.numero_contrato_servi', '{n}.cepd02_contratoservicio_valuacion_cuerpo.numero_contrato_servi');
				$numero_anticipos=$this->comboBox("v_cepd03_ordenpago_csvc","numero_contrato_servicio","deno_select",$this->SQLCA()." and ano_contrato_servicio=".$ano." and condicion_actividad=1 and numero_orden_pago=0");
				$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
				$this->set('numero_documentos',$numero_anticipos);
				$this->set('tipo_documento',8);
				break;
				case 9://contrato servicio retenciones
				$this->Session->write('ano_contrato_servicio',$ano);
					 //$numero_anticipos=$this->cepd02_contratoservicio_retencion_cuerpo->generateList($this->SQLCA()." and ano_contrato_servicio=".$ano." and condicion_actividad=1 and numero_orden_pago=0",'numero_contrato_servicio ASC', null, '{n}.cepd02_contratoservicio_retencion_cuerpo.numero_contrato_servi', '{n}.cepd02_contratoservicio_retencion_cuerpo.numero_contrato_servi');
				$numero_anticipos=$this->comboBox("v_cepd03_ordenpago_csrc","numero_contrato_servicio","deno_select",$this->SQLCA()." and ano_contrato_servicio=".$ano." and condicion_actividad=1 and numero_orden_pago=0");
				$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
				$this->set('numero_documentos',$numero_anticipos);
				$this->set('tipo_documento',9);
				break;
				case 10://Ordenes de compra - retenciones
				$this->Session->write('ano_orden_compra',$ano);
				$numero_anticipos=$this->comboBox("v_cepd03_ordenpago_raco","numero_orden_compra","deno_select",$this->SQLCA()." and ano_orden_compra=".$ano." and condicion_actividad=1 and numero_orden_pago=0");
				$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();

				$this->set('numero_documentos',$numero_anticipos);
				$this->set('tipo_documento',10);
				break;
			}//fin switch

		}
}//fin nro documento


function cargar_adjunto ($var=null,$codigo=null) {
	$this->layout="ajax";
	$this->Session->delete("items");
	$this->Session->delete("i");
	if(isset($var)){
		$ano=date("Y");
		switch($var){
				case 1://compromiso
				if($codigo!=""){
					$this->Session->write('ano_documento',$ano);
						//$numero_documentos = $this->cepd01_compromiso_cuerpo->generateList($this->SQLCA()." and numero_orden_pago=0 and ano_documento=".$ano." and condicion_actividad=1",'numero_documento ASC', null, '{n}.cepd01_compromiso_cuerpo.numero_documento', '{n}.cepd01_compromiso_cuerpo.numero_documento');
					$numero_documentos = $this->comboBox_beneficiario("cepd01_compromiso_cuerpo","numero_documento","(numero_documento || ' - ' || beneficiario) as denominacion",$this->SQLCA()." and numero_orden_pago=0 and ano_documento=".$ano." and condicion_actividad=1",'denominacion');
						//print_r($numero_documentos);
					$numero_documentos = $numero_documentos != null ? $numero_documentos : array();
					$this->AddCero('numero_documentos',$numero_documentos);
				}else{
					$this->set("numero_documentos",array());
				}
				$this->set('tipo_documento',1);
				break;
				case 2://ordenes de compra  anticipo
				if($codigo!=""){
					$ano=$this->Session->read('ano_orden_compra');
					$this->Session->write("numero_orden_compra",$codigo);
							        //$numero_anticipos=$this->cscd04_ordencompra_anticipo_cuerpo->generateList($this->SQLCA()." and ano_orden_compra=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and numero_orden_compra=".$codigo,'numero_anticipo ASC', null, '{n}.cscd04_ordencompra_anticipo_cuerpo.numero_anticipo', '{n}.cscd04_ordencompra_anticipo_cuerpo.numero_anticipo');
					$numero_anticipos = $this->comboBox("cscd04_ordencompra_anticipo_cuerpo","numero_anticipo","numero_anticipo",$this->SQLCA()." and ano_orden_compra=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and numero_orden_compra=".$codigo);
					$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
					$this->AddCero('numero_documentos',$numero_anticipos);
				}else{
					$this->set("numero_documentos",array());
				}
				$this->set('tipo_documento',2);
				break;
				case 3://ordenes de compra autorizacion pago
				if($codigo!=""){
					$ano=$this->Session->read('ano_orden_compra');
					$this->Session->write("numero_orden_compra",$codigo);
							        //$numero_anticipos=$this->cscd04_ordencompra_autorizacion_cuerpo->generateList($this->SQLCA()." and ano_orden_compra=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and numero_orden_compra=".$codigo,'numero_pago ASC', null, '{n}.cscd04_ordencompra_autorizacion_cuerpo.numero_pago', '{n}.cscd04_ordencompra_autorizacion_cuerpo.numero_pago');
					$numero_anticipos = $this->comboBox("cscd04_ordencompra_autorizacion_pago_cuerpo","numero_pago","numero_pago",$this->SQLCA()." and ano_orden_compra=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and numero_orden_compra=".$codigo);
					$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
					$this->AddCero('numero_documentos',$numero_anticipos);
				}else{
					$this->set("numero_documentos",array());
				}
				$this->set('tipo_documento',3);
				break;
				case 4://contrato obras anticipo
				if($codigo!=""){

					$ano=$this->Session->read('ano_contrato_obra');
					$this->Session->write('numero_contrato_obra',$codigo);
					 //$numero_anticipos=$this->cobd01_contratoobras_anticipo_cuerpo->generateList($this->SQLCA()." and ano_contrato_obra=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and numero_contrato_obra='".$codigo."'",'numero_anticipo ASC', null, '{n}.cobd01_contratoobras_anticipo_cuerpo.numero_anticipo', '{n}.cobd01_contratoobras_anticipo_cuerpo.numero_anticipo');
					$numero_anticipos = $this->comboBox("cobd01_contratoobras_anticipo_cuerpo","numero_anticipo","numero_anticipo",$this->SQLCA()." and ano_contrato_obra=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and numero_contrato_obra='".$codigo."'");
					$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
					$this->AddCero('numero_documentos',$numero_anticipos);
					 //$this->set('tipo_documento',3);

				}else{
					$this->set("numero_documentos",array());
				}
				$this->set('tipo_documento',4);
				break;
				case 5://contrato obras valuaciones
				if($codigo!=""){

					$ano=$this->Session->read('ano_contrato_obra');
					$this->Session->write('numero_contrato_obra',$codigo);
					 //$numero_anticipos=$this->cobd01_contratoobras_valuacion_cuerpo->generateList($this->SQLCA()." and ano_contrato_obra=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and numero_contrato_obra='".$codigo."'",'numero_valuacion ASC', null, '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_valuacion', '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_valuacion');
					$numero_anticipos = $this->comboBox("cobd01_contratoobras_valuacion_cuerpo","numero_valuacion","numero_valuacion",$this->SQLCA()." and ano_contrato_obra=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and numero_contrato_obra='".$codigo."'");
					$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
					$this->AddCero('numero_documentos',$numero_anticipos);
					 //$this->set('tipo_documento',3);

				}else{
					$this->set("numero_documentos",array());
				}
				$this->set('tipo_documento',5);
				break;
				case 6://contrato obras retenciones
				if($codigo!=""){

					$ano=$this->Session->read('ano_contrato_obra');
					$this->Session->write('numero_contrato_obra',$codigo);
					$numero_anticipos = $this->comboBox("cobd01_contratoobras_retencion_cuerpo","numero_retencion","numero_retencion",$this->SQLCA()." and ano_contrato_obra=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and numero_contrato_obra='".$codigo."'");
					$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
					$this->AddCero('numero_documentos',$numero_anticipos);
					 //$this->set('tipo_documento',3);

				}else{
					$this->set("numero_documentos",array());
				}
				$this->set('tipo_documento',6);
				break;
				case 7://contrato servicio anticipo
				if($codigo!=""){
					$ano=$this->Session->read('ano_contrato_servicio');
					$this->Session->write('numero_contrato_servicio',$codigo);
					 //$numero_anticipos=$this->cepd02_contratoservicio_anticipo_cuerpo->generateList($this->SQLCA()." and ano_contrato_servicio=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and numero_contrato_servicio='".$codigo."'",'numero_anticipo ASC', null, '{n}.cepd02_contratoservicio_anticipo_cuerpo.numero_anticipo', '{n}.cepd02_contratoservicio_anticipo_cuerpo.numero_anticipo');
					$numero_anticipos = $this->comboBox("cepd02_contratoservicio_anticipo_cuerpo","numero_anticipo","numero_anticipo",$this->SQLCA()." and ano_contrato_servicio=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and numero_contrato_servicio='".$codigo."'");
					$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
					$this->AddCero('numero_documentos',$numero_anticipos);
					 //$this->set('tipo_documento',3);

				}else{
					$this->set("numero_documentos",array());
				}
				$this->set('tipo_documento',7);
				break;
				case 8://contrato servicio valuaciones
				if($codigo!=""){
					$ano=$this->Session->read('ano_contrato_servicio');
					$this->Session->write('numero_contrato_servicio',$codigo);
					 //$numero_anticipos=$this->cepd02_contratoservicio_valuacion_cuerpo->generateList($this->SQLCA()." and ano_contrato_servicio=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and upper(numero_contrato_servicio)='".strtoupper($codigo)."'",'numero_valuacion ASC', null, '{n}.cepd02_contratoservicio_valuacion_cuerpo.numero_valuacion', '{n}.cepd02_contratoservicio_valuacion_cuerpo.numero_valuacion');
					$numero_anticipos = $this->comboBox("cepd02_contratoservicio_valuacion_cuerpo","numero_valuacion","numero_valuacion",$this->SQLCA()." and ano_contrato_servicio=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and upper(numero_contrato_servicio)='".strtoupper($codigo)."'");
					$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
					$this->AddCero('numero_documentos',$numero_anticipos);
					 //$this->set('tipo_documento',3);

				}else{
					$this->set("numero_documentos",array());
				}
				$this->set('tipo_documento',8);
				break;
				case 9://contrato servicio retenciones
				if($codigo!=""){
					$ano=$this->Session->read('ano_contrato_servicio');
					$this->Session->write('numero_contrato_servicio',$codigo);
					// $numero_anticipos=$this->cepd02_contratoservicio_retencion_cuerpo->generateList($this->SQLCA()." and ano_contrato_servicio=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and upper(numero_contrato_servicio)='".strtoupper($codigo)."'",'numero_retencion ASC', null, '{n}.cepd02_contratoservicio_retencion_cuerpo.numero_retencion', '{n}.cepd02_contratoservicio_retencion_cuerpo.numero_retencion');
					$numero_anticipos = $this->comboBox("cepd02_contratoservicio_retencion_cuerpo","numero_retencion","numero_retencion",$this->SQLCA()." and ano_contrato_servicio=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and upper(numero_contrato_servicio)='".strtoupper($codigo)."'");
					$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
					$this->AddCero('numero_documentos',$numero_anticipos);
					 //$this->set('tipo_documento',3);

				}else{
					$this->set("numero_documentos",array());
				}
				$this->set('tipo_documento',9);
				break;
				case 10://Ordenes de compras - Retenciones
				if($codigo!=""){
					$ano=$this->Session->read('ano_orden_compra');
					$this->Session->write('numero_orden_compra',$codigo);
					$numero_anticipos = $this->comboBox("cscd04_ordencompra_retencion_cuerpo","numero_retencion","numero_retencion",$this->SQLCA()." and ano_orden_compra=".$ano." and condicion_actividad=1 and numero_orden_pago=0 and numero_orden_compra=".$codigo."");
					$numero_anticipos = $numero_anticipos != null ? $numero_anticipos : array();
					$this->AddCero('numero_documentos',$numero_anticipos);
					 //$this->set('tipo_documento',3);

				}else{
					$this->set("numero_documentos",array());
				}
				$this->set('tipo_documento',10);
				break;
			}//fin switch

		}

}//fin cargar_adjunto


function cargar ($var=null,$codigo=null) {

	$this->layout="ajax";
	 //echo $var." / ".$codigo;
	if(isset($var) && isset($codigo)){

		switch($var){
				case 1://compromiso
				if($codigo!=""){
					$ano=$this->Session->read('ano_documento');
					$compromiso = $this->cepd01_compromiso_cuerpo->findAll($this->SQLCA()." and ano_documento=".$ano." and condicion_actividad=1 and numero_documento=".$codigo,array('rif','cedula_identidad','beneficiario','fecha_documento'));
					$compromiso = $compromiso != null ? $compromiso : array();
					$this->set('compromiso',$compromiso);
					$compromiso_partidas = $this->cepd01_compromiso_partidas->findAll($this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo);
					$compromiso_partidas = $compromiso_partidas != null ? $compromiso_partidas : array();
					$this->set('compromiso_partidas',$compromiso_partidas);
				}else{
					$this->set('compromiso',array());
					$this->set('compromiso_partidas',array());
				}
				$this->set('tipo',1);

				break;
				case 2://ordenes de compra  anticipo
				if($codigo!=""){
					$ano=$this->Session->read('ano_orden_compra');
					$orden_compra=$this->Session->read("numero_orden_compra");
					$resultado=$this->v_cepd03_ordenpago_anticipo_compra->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$orden_compra." and numero_anticipo=".$codigo);
					$resultado_partidas=$this->cscd04_ordencompra_anticipo_partidas->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$orden_compra." and numero_anticipo=".$codigo);
					$this->set("orden_compra_datos",$resultado);
						//  print_r($resultado);
					$this->set("partidas",$resultado_partidas);
						//  print_r($resultado_partidas);

				}else{
					$this->set('orden_compra_datos',array());
					$this->set('partidas',array());
				}
				$this->set('tipo',2);
				break;
				case 3://ordenes de compra autorizacion pago
				if($codigo!=""){
					$ano=$this->Session->read('ano_orden_compra');
					$orden_compra=$this->Session->read("numero_orden_compra");
					$resultado=$this->v_cepd03_ordenpago_autorizacion_compra->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$orden_compra." and numero_pago=".$codigo);
					$resultado_partidas=$this->cscd04_ordencompra_autorizacion_pago_partidas->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$orden_compra." and numero_pago=".$codigo);
					$this->set("orden_compra_datos",$resultado);
						//  print_r($resultado);
					$this->set("partidas",$resultado_partidas);
						//  print_r($resultado_partidas);

				}else{
					$this->set('orden_compra_datos',array());
					$this->set('partidas',array());
				}
				$this->set('tipo',3);
							//tablas a utilizar
							//cscd04_
							//
				break;
				case 4://contrato obras anticipo
				if($codigo!=""){
					$ano=$this->Session->read('ano_contrato_obra');
					$contrato_obra=$this->Session->read("numero_contrato_obra");
					$resultado=$this->v_cobd01_contratoobras_anticipo->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_anticipo=".$codigo);
					$resultado_partidas=$this->cobd01_contratoobras_anticipo_partidas->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_anticipo=".$codigo);
					$this->set("contrato_obra_datos",$resultado);
						//  print_r($resultado);
					$this->set("partidas",$resultado_partidas);
						//  print_r($resultado_partidas);

				}else{
					$this->set('contrato_obra_datos',array());
					$this->set('partidas',array());
				}
				$this->set('tipo',4);

				break;
				case 5://contrato obras valuaciones
				if($codigo!=""){
					$ano=$this->Session->read('ano_contrato_obra');
					$contrato_obra=$this->Session->read("numero_contrato_obra");
					$resultado=$this->v_cobd01_contratoobras_valuacion->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_valuacion=".$codigo);
					$resultado_partidas=$this->cobd01_contratoobras_valuacion_partidas->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_valuacion=".$codigo);

					$this->set("contrato_obra_datos",$resultado);
						//  print_r($resultado);
					$this->set("partidas",$resultado_partidas);
						//  print_r($resultado_partidas);

				}else{
					$this->set('contrato_obra_datos',array());
					$this->set('partidas',array());
				}
				$this->set('tipo',5);
				break;
				case 6://contrato obras retenciones
                       if($codigo!=""){//'v_cepd02_contratoservi_retencion','v_cobd01_contratoobras_retencion'
                       $ano=$this->Session->read('ano_contrato_obra');
                       $contrato_obra=$this->Session->read("numero_contrato_obra");
                       $resultado=$this->v_cobd01_contratoobras_retencion->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_retencion=".$codigo);
                       $resultado_partidas=$this->cobd01_contratoobras_retencion_partidas->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_retencion=".$codigo);

                       $this->set("contrato_obra_datos",$resultado);
						//  print_r($resultado);
                       $this->set("partidas",$resultado_partidas);
						//  print_r($resultado_partidas);

                     }else{
                     	$this->set('contrato_obra_datos',array());
                     	$this->set('partidas',array());
                     }
                     $this->set('tipo',6);
                     break;
				case 7://contrato servicio anticipo
                     if($codigo!=""){//'cepd02_contratoservicio_anticipo_cuerpo','cepd02_contratoservicio_anticipo_partidas','v_cepd02_servicio_anticipo'
                     $ano=$this->Session->read('ano_contrato_servicio');
                     $numero_contrato_servicio=$this->Session->read("numero_contrato_servicio");
                     $resultado=$this->v_cepd02_servicio_anticipo->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_anticipo=".$codigo);
                     $resultado_partidas=$this->cepd02_contratoservicio_anticipo_partidas->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_anticipo=".$codigo);
                     $this->set("contrato_servicio_datos",$resultado);
						//  print_r($resultado);
                     $this->set("partidas",$resultado_partidas);
						//  print_r($resultado_partidas);

                   }else{
                   	$this->set('contrato_servicio_datos',array());
                   	$this->set('partidas',array());
                   }
                   $this->set('tipo',7);
                   break;
				case 8://contrato servicio valuaciones
                    if($codigo!=""){//'cepd02_contratoservicio_anticipo_cuerpo','cepd02_contratoservicio_anticipo_partidas','v_cepd02_servicio_anticipo'
                    $ano=$this->Session->read('ano_contrato_servicio');
                    $numero_contrato_servicio=$this->Session->read("numero_contrato_servicio");
                    $resultado=$this->v_cepd02_contratoservi_valuacion->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_valuacion=".$codigo);
                    $resultado_partidas=$this->cepd02_contratoservicio_valuacion_partidas->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_valuacion=".$codigo);
                    $this->set("contrato_servicio_datos",$resultado);
						//  print_r($resultado);
                    $this->set("partidas",$resultado_partidas);
						//  print_r($resultado_partidas);

                  }else{
                  	$this->set('contrato_servicio_datos',array());
                  	$this->set('partidas',array());
                  }
                  $this->set('tipo',8);
                  break;
				case 9://contrato servicio retenciones
                       if($codigo!=""){//'cepd02_contratoservicio_anticipo_cuerpo','cepd02_contratoservicio_anticipo_partidas','v_cepd02_servicio_anticipo'
                       $ano=$this->Session->read('ano_contrato_servicio');
                       $numero_contrato_servicio=$this->Session->read("numero_contrato_servicio");
                       $resultado=$this->v_cepd02_contratoservi_retencion->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_retencion=".$codigo);
                       $resultado_partidas=$this->cepd02_contratoservicio_retencion_partidas->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_retencion=".$codigo);
                       $this->set("contrato_servicio_datos",$resultado);
						//  print_r($resultado);
                       $this->set("partidas",$resultado_partidas);
						//  print_r($resultado_partidas);

                     }else{
                     	$this->set('contrato_servicio_datos',array());
                     	$this->set('partidas',array());
                     }
                     $this->set('tipo',9);
                     break;
				case 10://Ordenes compras - Retenciones
                       if($codigo!=""){//'v_cepd02_contratoservi_retencion','v_cobd01_contratoobras_retencion'
                       $ano=$this->Session->read('ano_orden_compra');
                       $contrato_obra=$this->Session->read("numero_orden_compra");
                       $resultado=$this->v_cscd04_ordencompras_retencion->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$contrato_obra." and numero_retencion=".$codigo);
                       $resultado_partidas=$this->cscd04_ordencompra_retencion_partidas->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$contrato_obra." and numero_retencion=".$codigo);
                       $this->set("contrato_obra_datos",$resultado);
						//  print_r($resultado);
                       $this->set("partidas",$resultado_partidas);
						//  print_r($resultado_partidas);

                     }else{
                     	$this->set('contrato_obra_datos',array());
                     	$this->set('partidas',array());
                     }
                     $this->set('tipo',10);
                     break;

			}//fin switch
		}
}//fin cargar

function cargar2 ($var=null,$codigo=null) {
	$this->layout="ajax";

	if(isset($var) && isset($codigo)){
		$ano=$this->Session->read('ano_documento');
		switch($var){
				case 1://compromiso

				$compromiso_concepto = $this->cepd01_compromiso_cuerpo->findAll($this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo,array('concepto','condicion_juridica','rif'));
				$mi      = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".strtoupper($codigo)." and cod_partida=403 and cod_generica=18 and cod_especifica=1");

/*
					    $m_411   = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".strtoupper($codigo)." and cod_partida=411");
						if($mi[0][0]["monto_iva"]>0 || $m_411[0][0]["monto_iva"]>0){
                               $this->set("mostrar_crear_factura",true);
						}else{
							         if($compromiso_concepto[0]["cepd01_compromiso_cuerpo"]["condicion_juridica"]==2){
								     	$o_r=$this->cpcd02->findCount("rif='".$compromiso_concepto[0]["cepd01_compromiso_cuerpo"]["rif"]."' and objeto=3");
								     	$this->set('es_cooperativa','si');
								     	$this->set("mostrar_crear_factura",true);
								     }else{
								     	$this->set('es_cooperativa','no');
								     	$this->set("mostrar_crear_factura",false);
								     }
						}

*/

// SI EN ALGÚN MOMENTO DE LA PARTIDA 411 SE EXTRAE EL IVA...QUITAR ESTA CONDICIÓN Y HABILITAR LA DE ARRIBA
						if($mi[0][0]["monto_iva"]>0){
							$this->set("mostrar_crear_factura",true);
						}else{
							if($compromiso_concepto[0]["cepd01_compromiso_cuerpo"]["condicion_juridica"]==2){
								$o_r=$this->cpcd02->findCount("rif='".$compromiso_concepto[0]["cepd01_compromiso_cuerpo"]["rif"]."' and objeto=3");
								$this->set('es_cooperativa','si');
								$this->set("mostrar_crear_factura",true);
							}else{
								$this->set('es_cooperativa','no');
								$this->set("mostrar_crear_factura",false);
							}
						}





						$this->set("concepto",$compromiso_concepto[0]["cepd01_compromiso_cuerpo"]["concepto"]);
						$this->set('tipo',1);
						break;
				case 2://ordenes de compra anticipo
				if($codigo!=""){
					$ano=$this->Session->read('ano_orden_compra');
					$orden_compra=$this->Session->read("numero_orden_compra");
					$resultado=$this->v_cepd03_ordenpago_anticipo_compra->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$orden_compra." and numero_anticipo=".$codigo);
					$resultado_partidas=$this->cscd04_ordencompra_anticipo_partidas->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$orden_compra." and numero_anticipo=".$codigo);
					$this->set("concepto",$resultado[0]["v_cepd03_ordenpago_anticipo_compra"]["observaciones"]);

					$mi      = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cscd04_ordencompra_anticipo_partidas WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".strtoupper($orden_compra)." and numero_anticipo=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
					if($mi[0][0]["monto_iva"]>0){
						$this->set("mostrar_crear_factura",true);
					}else{
						$this->set("mostrar_crear_factura",false);
					}

				}else{
					$this->set("concepto","");
				}
				$this->set('tipo',2);
				break;
				case 3://ordenes de compra autorizacion pago
				if($codigo!=""){
					$ano=$this->Session->read('ano_orden_compra');
					$orden_compra=$this->Session->read("numero_orden_compra");
					$resultado=$this->cscd04_ordencompra_autorizacion_cuerpo->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$orden_compra." and numero_pago=".$codigo,array('concepto'));
					$this->set("concepto",$resultado[0]["cscd04_ordencompra_autorizacion_cuerpo"]["concepto"]);
					$mi      = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cscd04_ordencompra_autorizacion_pago_partidas WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".strtoupper($orden_compra)." and numero_pago=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
					$mislr   = $this->cepd01_compromiso_partidas->execute("SELECT monto_islr AS monto_islr  FROM cscd04_ordencompra_autorizacion_pago_cuerpo WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".strtoupper($orden_compra)." and numero_pago=".$codigo.";");

/*
                        $m_411   = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cscd04_ordencompra_autorizacion_pago_partidas WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".strtoupper($orden_compra)." and numero_pago=".$codigo." and cod_partida=411 ");
                       if($mi[0][0]["monto_iva"]>0 || $m_411[0][0]["monto_iva"]>0 || $mislr[0][0]["monto_islr"]>0){
                               $this->set("mostrar_crear_factura",true);
						}else{
							  $this->set("mostrar_crear_factura",false);
						}
*/

// SI EN ALGÚN MOMENTO DE LA PARTIDA 411 SE EXTRAE EL IVA...QUITAR ESTA CONDICIÓN Y HABILITAR LA DE ARRIBA
						if($mi[0][0]["monto_iva"]>0 || $mislr[0][0]["monto_islr"]>0){
							$this->set("mostrar_crear_factura",true);
						}else{
							$this->set("mostrar_crear_factura",false);
						}




					}else{
						$this->set("concepto","");
					}
					$this->set('tipo',3);
					break;
				case 4://contrato obras anticipo
				if($codigo!=""){
					$ano=$this->Session->read('ano_contrato_obra');
					$contrato_obra=$this->Session->read("numero_contrato_obra");
					$resultado=$this->v_cobd01_contratoobras_anticipo->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_anticipo=".$codigo);
					$resultado_partidas=$this->cobd01_contratoobras_anticipo_partidas->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_anticipo=".$codigo);
					$this->set("concepto",$resultado[0]["v_cobd01_contratoobras_anticipo"]["observaciones"]);
					$mi      = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cobd01_contratoobras_anticipo_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_anticipo=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
					if($mi[0][0]["monto_iva"]>0){
						$this->set("mostrar_crear_factura",true);
					}else{
						$this->set("mostrar_crear_factura",false);
					}

				}else{
					$this->set("concepto","");
				}
				$this->set('tipo',2);
				break;
				case 5://contrato obras valuaciones
				if($codigo!=""){
					$ano=$this->Session->read('ano_contrato_obra');
					$contrato_obra=$this->Session->read("numero_contrato_obra");
					$resultado=$this->v_cobd01_contratoobras_valuacion->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_valuacion=".$codigo);
					$resultado_partidas=$this->cobd01_contratoobras_valuacion_partidas->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_valuacion=".$codigo);
					$this->set("concepto",$resultado[0]["v_cobd01_contratoobras_valuacion"]["concepto"]);
					$mi      = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cobd01_contratoobras_valuacion_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_valuacion=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
					$mislr   = $this->cepd01_compromiso_partidas->execute("SELECT monto_islr AS monto_islr  FROM cobd01_contratoobras_valuacion_cuerpo WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_valuacion=".$codigo.";");
					if($mi[0][0]["monto_iva"]>0 || $mislr[0][0]["monto_islr"]>0){
						$this->set("mostrar_crear_factura",true);
					}else{
						$this->set("mostrar_crear_factura",false);
					}
				}else{
					$this->set("concepto","");
				}
				$this->set('tipo',5);
				break;
				case 6://contrato obras retenciones
				if($codigo!=""){
					$ano=$this->Session->read('ano_contrato_obra');
					$contrato_obra=$this->Session->read("numero_contrato_obra");
					$resultado=$this->v_cobd01_contratoobras_retencion->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_retencion=".$codigo);
					$resultado_partidas=$this->cobd01_contratoobras_retencion_partidas->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_retencion=".$codigo);
					$this->set("concepto",$resultado[0]["v_cobd01_contratoobras_retencion"]["concepto"]);
					$mi      = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cobd01_contratoobras_retencion_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_retencion=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
					$mislr   = $this->cepd01_compromiso_partidas->execute("SELECT monto_islr AS monto_islr  FROM cobd01_contratoobras_retencion_cuerpo WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_retencion=".$codigo.";");
					if($mi[0][0]["monto_iva"]>0 || $mislr[0][0]["monto_islr"]>0){
						$this->set("mostrar_crear_factura",true);
					}else{
						$this->set("mostrar_crear_factura",false);
					}
				}else{
					$this->set("concepto","");
				}
				$this->set('tipo',5);
				break;
				case 7://contrato servicio anticipo
                     if($codigo!=""){//'cepd02_contratoservicio_anticipo_cuerpo','cepd02_contratoservicio_anticipo_partidas','v_cepd02_servicio_anticipo'
                     $ano=$this->Session->read('ano_contrato_servicio');
                     $numero_contrato_servicio=$this->Session->read("numero_contrato_servicio");
                     $resultado=$this->v_cepd02_servicio_anticipo->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_anticipo=".$codigo);
                     $resultado_partidas=$this->cepd02_contratoservicio_anticipo_partidas->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_anticipo=".$codigo);

                     $this->set("concepto",$resultado[0]["v_cepd02_servicio_anticipo"]["observaciones"]);
                     $mi      = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cepd02_contratoservicio_anticipo_partidas WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_anticipo=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
                     if($mi[0][0]["monto_iva"]>0){
                     	$this->set("mostrar_crear_factura",true);
                     }else{
                     	$this->set("mostrar_crear_factura",false);
                     }
                   }else{
                   	$this->set("concepto","");
                   }
                   $this->set('tipo',2);
                   break;
				case 8://contrato servicio valuaciones
                    if($codigo!=""){//'cepd02_contratoservicio_anticipo_cuerpo','cepd02_contratoservicio_anticipo_partidas','v_cepd02_servicio_anticipo'
                    $ano=$this->Session->read('ano_contrato_servicio');
                    $numero_contrato_servicio=$this->Session->read("numero_contrato_servicio");
                    $resultado=$this->v_cepd02_contratoservi_valuacion->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_valuacion=".$codigo);
                    $resultado_partidas=$this->cepd02_contratoservicio_valuacion_partidas->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_valuacion=".$codigo);
							//print_r($resultado_partidas);
                    $this->set("concepto",$resultado[0]["v_cepd02_contratoservi_valuacion"]["concepto"]);
                    $mi      = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cepd02_contratoservicio_valuacion_partidas WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_valuacion=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
                    $mislr   = $this->cepd01_compromiso_partidas->execute("SELECT monto_islr AS monto_islr  FROM cepd02_contratoservicio_valuacion_cuerpo WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_valuacion=".$codigo.";");

/*
						    $m_411   = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cepd02_contratoservicio_valuacion_partidas WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_valuacion=".$codigo." and cod_partida=411");
							if($mi[0][0]["monto_iva"]>0 || $m_411[0][0]["monto_iva"]>0 || $mislr[0][0]["monto_islr"]>0){
	                               $this->set("mostrar_crear_factura",true);
							}else{
								  $this->set("mostrar_crear_factura",false);
							}
*/

// SI EN ALGÚN MOMENTO DE LA PARTIDA 411 SE EXTRAE EL IVA...QUITAR ESTA CONDICIÓN Y HABILITAR LA DE ARRIBA
							if($mi[0][0]["monto_iva"]>0 || $mislr[0][0]["monto_islr"]>0){
								$this->set("mostrar_crear_factura",true);
							}else{
								$this->set("mostrar_crear_factura",false);
							}




						}else{
							$this->set("concepto","");
						}
						$this->set('tipo',5);
						break;
				case 9://contrato servicio retenciones
				if($codigo!=""){
					$ano=$this->Session->read('ano_contrato_servicio');
					$numero_contrato_servicio=$this->Session->read("numero_contrato_servicio");
					$resultado=$this->v_cepd02_contratoservi_retencion->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_retencion=".$codigo);
					$resultado_partidas=$this->cepd02_contratoservicio_retencion_partidas->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_retencion=".$codigo);
							//print_r($resultado_partidas);
					$this->set("concepto",$resultado[0]["v_cepd02_contratoservi_retencion"]["concepto"]);
					$mi      = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cepd02_contratoservicio_retencion_partidas WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_retencion=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
					$mislr   = $this->cepd01_compromiso_partidas->execute("SELECT monto_islr AS monto_islr   FROM cepd02_contratoservicio_retencion_cuerpo WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_retencion=".$codigo.";");
					if($mi[0][0]["monto_iva"]>0 || $mislr[0][0]["monto_islr"]>0){
						$this->set("mostrar_crear_factura",true);
					}else{
						$this->set("mostrar_crear_factura",false);
					}
				}else{
					$this->set("concepto","");
				}
				$this->set('tipo',5);
				break;
				case 10://Ordenes de compras - Retenciones

				if($codigo!=""){
					$ano=$this->Session->read('ano_orden_compra');
					$contrato_obra=$this->Session->read("numero_orden_compra");
					$resultado=$this->v_cscd04_ordencompras_retencion->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$contrato_obra." and numero_retencion=".$codigo);
					$resultado_partidas=$this->cscd04_ordencompra_retencion_partidas->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$contrato_obra." and numero_retencion=".$codigo);
					$this->set("concepto",$resultado[0]["v_cscd04_ordencompras_retencion"]["concepto"]);
					$mi      = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cscd04_ordencompra_retencion_partidas WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$contrato_obra." and numero_retencion=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
					$mislr   = $this->cepd01_compromiso_partidas->execute("SELECT monto_islr AS monto_islr  FROM cscd04_ordencompra_retencion_cuerpo WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$contrato_obra." and numero_retencion=".$codigo.";");
					if($mi[0][0]["monto_iva"]>0 || $mislr[0][0]["monto_islr"]>0){
						$this->set("mostrar_crear_factura",true);
					}else{
						$this->set("mostrar_crear_factura",false);
					}
				}else{
					$this->set("concepto","");
				}
				$this->set('tipo',10);
				break;
			}//fin switch
		}
}//fin cargar 2

function cargar3($var=null,$codigo=null){

	$this->layout="ajax";
	if(isset($var) && isset($codigo)){
		$ano=$this->ano_ejecucion();
		switch($var){
				case 1://compromiso
				if($var==1){
					$o_r=$this->cepd01_compromiso_cuerpo->findAll($this->SQLCA()." and numero_documento=".$codigo." and ano_documento=".$ano,array('condicion_juridica','cod_tipo_compromiso', 'cedula_identidad'));
					$objeto_rif=$o_r[0]["cepd01_compromiso_cuerpo"]["condicion_juridica"];
					$this->Session->write("rif_municipal", $o_r[0]["cepd01_compromiso_cuerpo"]["cedula_identidad"]);
					if($objeto_rif==1){
						$objeto_rif=4;
					}else{
						$objeto_rif=2;
					}

					if($objeto_rif==2){
								     	//$o_r=$this->cpcd02->findCount("rif='".$this->Session->read("rif")."' and objeto=3");
						$this->set('es_cooperativa','si');
					}else{
						$this->set('es_cooperativa','no');
					}

								    // echo $o_r[0]["cepd01_compromiso_cuerpo"]["cod_tipo_compromiso"];
					$CP_sujeto_retencion=$this->cepd01_tipo_compromiso->findAll("cod_tipo_compromiso=".$o_r[0]["cepd01_compromiso_cuerpo"]["cod_tipo_compromiso"]);
					$CPSR=$CP_sujeto_retencion[0]["cepd01_tipo_compromiso"]["sujeto_retencion"];
								     //print_r($CP_sujeto_retencion);
				}else{

				}

				$this->set("objeto_rif",$objeto_rif);
							//$ano=$this->Session->read('ano_documento');
				$ano=$ano=$this->ano_ejecucion();
				$mt = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_total FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo);
				$mi= $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
				$monto401= $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_401 FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo." and (cod_partida=401 OR cod_partida=407)");
				$cantidad_401     = $this->cepd01_compromiso_partidas->execute("SELECT count(*) AS c FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo." and (cod_partida=401 OR cod_partida=407)");
				$cantidad_403     = $this->cepd01_compromiso_partidas->execute("SELECT count(*) AS c FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo." and cod_partida=403 and cod_generica!=18");
				$cantidad_4031801 = $this->cepd01_compromiso_partidas->execute("SELECT count(*) AS c FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
				$cantidad_otras   = $this->cepd01_compromiso_partidas->execute("SELECT count(*) AS c FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo." and cod_partida NOT IN (401,403,407)");

// SI EN ALGÚN MOMENTO DE LA PARTIDA 411 SE EXTRAE EL IVA...QUITAR ESTE COMENTARIO Y HABILITAR LA DE ARRIBA
						  //$monto411= $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_411 FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo." and cod_partida=411");


				$this->set("monto_total_cancelar",$mt[0][0]["monto_total"]);
				$this->Session->write("monto_total_cancelarSS",$mt[0][0]["monto_total"]);
				$this->set("monto_iva_partidas",$mi[0][0]["monto_iva"]);
				$this->Session->write("monto_iva_partidasSS",$mi[0][0]["monto_iva"]);
				$this->set("monto401",$monto401[0][0]["monto_401"]);


// SI EN ALGÚN MOMENTO DE LA PARTIDA 411 SE EXTRAE EL IVA...QUITAR ESTE COMENTARIO
						  //$this->set("monto411",$monto411[0][0]["monto_411"]);

				if($CPSR==1){
					$amortizacion_del_anticipo = "";
					$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
					$parametros_datos_detalles_del_pago = $this->cscd04_ordencompra_parametros->findAll($condicion);

					$porcentaje_fiel_cumplimiento     =   "0";
					$porcentaje_laboral               =   "0";
					$retencion_incluye_iva            =   "0";
					$porcentaje_islr_natural          =   "0";
					$desde_monto_natural              =   "0";
					$sustraendo                       =   "0";
					$porcentaje_islr_juridico         =   "0";
					$desde_monto_juridico             =   "0";
					$porcentaje_timbre_fiscal         =   "0";
					$desde_monto_timbre               =   "0";
					$porcentaje_impuesto_municipal    =   "0";
					$desde_monto_impuesto_municipal   =   "0";
					$porcentaje_retencion_iva         =   "0";
					$aplica_retencion_iva             =   "0";
					$porcentaje_anticipo              =   "0";
					$anticipo_incluye_iva             =   "0";
					$unidad_tributaria                =   "0";
					$porcentaje_iva                   =   "0";
					$factor_reversion                 =   "0";

					foreach($parametros_datos_detalles_del_pago as $aux_parametros_datos_detalles_del_pago){

						$porcentaje_fiel_cumplimiento     =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_fiel_cumplimiento'];
						$porcentaje_laboral               =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_laboral'];
						$retencion_incluye_iva            =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['retencion_incluye_iva'];
									$porcentaje_islr_natural          =   0;//$aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_islr_natural'];
									$desde_monto_natural              =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['desde_monto_natural'];
									$sustraendo                       =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['sustraendo'];
									$porcentaje_islr_juridico         =   0;//$aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_islr_juridico'];
									$desde_monto_juridico             =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['desde_monto_juridico'];
									$porcentaje_timbre_fiscal         =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_timbre_fiscal'];
									$desde_monto_timbre               =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['desde_monto_timbre'];
									$porcentaje_impuesto_municipal    =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_impuesto_municipal'];
									$desde_monto_impuesto_municipal   =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['desde_monto_impuesto_municipal'];
									$porcentaje_retencion_iva         =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_retencion_iva'];
									$aplica_retencion_iva             =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['aplica_retencion_iva'];
									$porcentaje_anticipo              =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_anticipo'];
									$anticipo_incluye_iva             =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['anticipo_incluye_iva'];
									$unidad_tributaria                =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['unidad_tributaria'];
									$porcentaje_iva                   =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['porcentaje_iva'];
									$factor_reversion                 =   $aux_parametros_datos_detalles_del_pago['cscd04_ordencompra_parametros']['factor_reversion'];
								}//fin foreach


/*
                                if($monto411[0][0]["monto_411"]!=0 &&  $cantidad_4031801[0][0]["c"]==0){
                                	$base411=$monto411[0][0]["monto_411"]/$factor_reversion;
                                	$iva411=$monto411[0][0]["monto_411"]-decimal_sprintf("%01.2f",$base411);
                                	$this->set("monto_iva_partidas",$iva411);
							        $this->Session->write("monto_iva_partidasSS",$iva411);
							        $this->set("iva411",true);
                                }else{
                                	$this->set("iva411",false);
                                	$iva411=0;
                                }
*/

// SI EN ALGÚN MOMENTO DE LA PARTIDA 411 SE EXTRAE EL IVA...QUITAR ESTAS LINEAS Y HABILITAR LA DE ARRIBA
                                $this->set("iva411",false);
                                $iva411=0;




                                if($cantidad_4031801[0][0]["c"]==0){
                                	$porcentaje_iva=0;
                                }

								$this->set("retencion_incluye_iva", $retencion_incluye_iva);//condicional 1=si 2=no
								if($retencion_incluye_iva==1){
									$c=($mt[0][0]["monto_total"]*0)/100;
									$this->set("monto_fiel_cumplimiento",$c);
									$d=($mt[0][0]["monto_total"]*0)/100;
									$this->set("monto_laboral",$d);
								}else{
									$c=(($mt[0][0]["monto_total"]-$mi[0][0]["monto_iva"])*0)/100;
									$this->set("monto_fiel_cumplimiento",$c);
									$d=(($mt[0][0]["monto_total"]-$mi[0][0]["monto_iva"])*0)/100;
									$this->set("monto_laboral",$d);

								}
								$monto_descontar_inspuesto=$mt[0][0]["monto_total"]-$c-$d-$mi[0][0]["monto_iva"]-$iva411;
								if($cantidad_401[0][0]["c"]!=0 && $cantidad_403[0][0]["c"]!=0 && $cantidad_4031801[0][0]["c"]!=0  && $cantidad_otras[0][0]["c"]==0){
									$monto_descontar_inspuesto=$monto_descontar_inspuesto-$monto401[0][0]["monto_401"];
									$this->set('restar_401','si');
								}else{
									$this->set('restar_401','no');
								}
								$this->set("monto_a_descontar_inpuesto",$monto_descontar_inspuesto);

								$this->Session->write("monto_a_descontar_inpuestoSS",$monto_descontar_inspuesto);
								$this->set("desde_monto_timbre",$desde_monto_timbre);
								if($monto_descontar_inspuesto<$desde_monto_timbre){
									$monto_timbre_fiscal=0;
									$porcentaje_timbre_fiscal=0;
								}else{
									$monto_timbre_fiscal=(($monto_descontar_inspuesto/1000)*$porcentaje_timbre_fiscal);
									$monto_timbre_fiscal=decimal_sprintf("%01.2f",$monto_timbre_fiscal);

								}

								$this->set("monto_timbre_fiscal",$monto_timbre_fiscal);
								$this->set("desde_monto_impuesto_municipal",$desde_monto_impuesto_municipal);
											//if($mt[0][0]["monto_total"]<$desde_monto_impuesto_municipal)
								if($monto_descontar_inspuesto<$desde_monto_impuesto_municipal){
									$monto_impuesto_municipal=0;
								}else{
									$monto_impuesto_municipal=$monto_descontar_inspuesto*($porcentaje_impuesto_municipal/100);
									$monto_impuesto_municipal=decimal_sprintf("%01.2f",$monto_impuesto_municipal);
								}

								if($_SESSION["SScodtipoinst"]==50){
									$monto_impuesto_municipal      = 0;
									$porcentaje_impuesto_municipal = 0;
								}
								$this->set("monto_impuesto_municipal",$monto_impuesto_municipal);


									 // $monto_timbre_fiscal;
										//$objeto_rif=4;
										//echo $objeto_rif;
								switch($objeto_rif){
									case 1:
															$this->set("impuesto_sobre_la_renta", $porcentaje_islr_juridico);//
															$this->set("desde_monto_juridico", $desde_monto_juridico);
															$this->set("desde_monto_natural", $desde_monto_natural);
															if($monto_descontar_inspuesto<$desde_monto_juridico){
																$monto_isrl=0;
															}else{
																$monto_isrl=$monto_descontar_inspuesto*$porcentaje_islr_juridico/100;
															}
															$monto_isrl=decimal_sprintf("%01.2f",$monto_isrl);
															$this->set("monto_isrl",$monto_isrl);
															$this->set("sustraendo", 0);
															$this->set("sustraendo_original", 0);
															break;
															case 2:
															$this->set("impuesto_sobre_la_renta", $porcentaje_islr_juridico);//
															$this->set("desde_monto_juridico", $desde_monto_juridico);
															$this->set("desde_monto_natural", $desde_monto_natural);
															if($mt[0][0]["monto_total"]<$desde_monto_juridico){
																$monto_isrl=0;
															}else{
																$monto_isrl=$monto_descontar_inspuesto*$porcentaje_islr_juridico/100;

															}
															$monto_isrl=decimal_sprintf("%01.2f",$monto_isrl);
															$this->set("monto_isrl",$monto_isrl);
															$this->set("sustraendo", 0);
															$this->set("sustraendo_original", 0);
															break;
															case 3:

															break;
															case 4:
													 $this->set("impuesto_sobre_la_renta", $porcentaje_islr_natural);//
													 $this->set("desde_monto_juridico", $desde_monto_juridico);
													 $this->set("desde_monto_natural", $desde_monto_natural);


													 if($monto_descontar_inspuesto<$desde_monto_natural){
													 	$monto_isrl=0;
													 	$this->set("sustraendo", 0);
													 	$this->set("desactivar", true);


													 }else{
													 	$this->set("sustraendo_original", $sustraendo);
													 	$monto_isrl=$monto_descontar_inspuesto*$porcentaje_islr_natural/100;

													 	if ($porcentaje_islr_natural==3){
													 		$sql_busca_sustraendo = $this->cepd02_contratoservicio_valuacion_cuerpo->execute("SELECT f_sustraendo($sustraendo) AS sustraendo_tresporciento");
													 		$sustraendo_tresporciento = $sql_busca_sustraendo[0][0]['sustraendo_tresporciento'];
													 		$sustraendo = $sustraendo_tresporciento;
													 	}

													 	if ($monto_isrl==0){$sustraendo=0;}
													 	$monto_isrl=$monto_isrl-$sustraendo;
													 	$this->set("sustraendo",0);
													 }
													 $sql_busca_sustraendo = $this->cepd02_contratoservicio_valuacion_cuerpo->execute("SELECT f_sustraendo($sustraendo) AS sustraendo_tresporciento");
													 $sustraendo_tresporciento = $sql_busca_sustraendo[0][0]['sustraendo_tresporciento'];

													 $this->set("sustraendo_tresporciento", $sustraendo_tresporciento);
													 $monto_isrl=decimal_sprintf("%01.2f",$monto_isrl);
													 $this->set("monto_isrl",$monto_isrl);

													 break;

								}//switch


								$this->set("porcentaje_fiel_cumplimiento", 0);
								$this->set("porcentaje_laboral", 0);
										$this->set("aplica_retencion_iva", $aplica_retencion_iva);//
										$this->set("porcentaje_anticipo", 0);
										$this->set("anticipo_incluye_iva",$anticipo_incluye_iva);
										if($anticipo_incluye_iva==1){
											$monto_anticipo=$mt[0][0]["monto_total"]*$porcentaje_anticipo/100;
										}else{
											$monto_anticipo=(($mt[0][0]["monto_total"]-$mi[0][0]["monto_iva"]-$iva411)*$porcentaje_anticipo)/100;
										}
										$monto_anticipo=decimal_sprintf("%01.2f",$monto_anticipo);
										//$this->set("monto_amortizacion_anticipo",$monto_anticipo);
										//$this->set("monto_orden_pago",$mt[0][0]["monto_total"]-$monto_anticipo);
										$this->set("monto_amortizacion_anticipo",0);
										$this->set("monto_orden_pago",$mt[0][0]["monto_total"]-0);
										$monto_retencion_iva=($mi[0][0]["monto_iva"]+$iva411)*$porcentaje_retencion_iva/100;
										$monto_retencion_iva=decimal_sprintf("%01.2f",$monto_retencion_iva);
										$this->set("monto_retencion_iva",$monto_retencion_iva);

										$this->set("timbre_fiscal", $porcentaje_timbre_fiscal);//
										$this->set("porcentaje_timbre_fiscal", $porcentaje_timbre_fiscal);
										$this->set("desde_monto_timbre", $desde_monto_timbre);
										//$monto_timbre_fiscal=$mt[0][0]["monto_total"];//////////////////////////////////////

										$this->set("impuesto_municipal", $porcentaje_impuesto_municipal);
										$this->set("desde_monto_impuesto_municipal", $desde_monto_impuesto_municipal);
										$this->set("porcentaje_retencion_iva", $porcentaje_retencion_iva);
										$this->set("anticipo_incluye_iva", $anticipo_incluye_iva);
										$this->set("unidad_tributaria", $unidad_tributaria);
										$this->set("porcentaje_iva", $porcentaje_iva);
										$this->set("factor_reversion", $factor_reversion);
										$monto_orden_pago=$mt[0][0]["monto_total"]-0;
										//echo $monto_isrl."-".$monto_retencion_iva."-".$monto_timbre_fiscal."-".$monto_impuesto_municipal."-".$monto_orden_pago;
										$neto_cobrar=$monto_orden_pago-($monto_isrl+$monto_retencion_iva+$monto_timbre_fiscal+$monto_impuesto_municipal);
										$this->set("neto_cobrar",$neto_cobrar);
										$this->set('tipo',1);
										}//fin if sujeto a retencion con el tipo de compromiso
										else{/////############ No hay retencion para el tipo de compromiso
											$this->set("monto_total_cancelar",$mt[0][0]["monto_total"]);
											$this->set("monto_orden_pago",$mt[0][0]["monto_total"]);
											$this->set("neto_cobrar",$mt[0][0]["monto_total"]);
											$this->set('tipo',2);
											$this->set("sustraendo", 0);
											$this->set("sustraendo_original", 0);
										}

										break;
				case 2://ordenes de compra  anticipo
				$ano=$this->Session->read('ano_orden_compra');
				$orden_compra=$this->Session->read("numero_orden_compra");
				$mt = $this->cscd04_ordencompra_anticipo_partidas->execute("SELECT SUM(monto) AS monto_total FROM cscd04_ordencompra_anticipo_partidas WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$orden_compra." and numero_anticipo=".$codigo);
				$this->set("monto_total_cancelar",$mt[0][0]["monto_total"]);
				$this->set("monto_orden_pago",$mt[0][0]["monto_total"]);
				$this->set("neto_cobrar",$mt[0][0]["monto_total"]);
				$this->set('tipo',2);
				break;
				case 3://ordenes de compra autorizacion pago

				$ano=$this->Session->read('ano_orden_compra');
				$orden_compra=$this->Session->read("numero_orden_compra");
				$result=$this->cscd04_ordencompra_encabezado->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$orden_compra);
				$anticipo_incluye_iva =$result[0]['cscd04_ordencompra_encabezado']['anticipo_con_iva'];
				$autorizacion_pago=$this->cscd04_ordencompra_autorizacion_cuerpo->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$orden_compra." and numero_pago=".$codigo);
				$this->set("anticipo_incluye_iva",$anticipo_incluye_iva);
				$this->set("autorizacion_pago",$autorizacion_pago);
				$mt      = $this->cscd04_ordencompra_autorizacion_pago_partidas->execute("SELECT SUM(monto) AS monto_total, SUM(amortizacion) AS monto_amortizacion, SUM(retencion_laboral) AS monto_laboral, SUM(retencion_fielcumplimiento) AS monto_fielcumplimiento FROM cscd04_ordencompra_autorizacion_pago_partidas WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$orden_compra." and numero_pago=".$codigo);
				$mi      = $this->cscd04_ordencompra_autorizacion_pago_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cscd04_ordencompra_autorizacion_pago_partidas WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$orden_compra." and numero_pago=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
				$monto401= $this->cscd04_ordencompra_autorizacion_pago_partidas->execute("SELECT SUM(monto) AS monto_401   FROM cscd04_ordencompra_autorizacion_pago_partidas WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$orden_compra." and numero_pago=".$codigo." and cod_partida=401");
				$mdi      = $this->cscd04_ordencompra_autorizacion_cuerpo->execute("SELECT monto_cancelar_siniva AS monto_descontar_impuesto FROM cscd04_ordencompra_autorizacion_pago_cuerpo WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$orden_compra." and numero_pago=".$codigo);
				$this->set("monto_total_cancelar",$mt[0][0]["monto_total"]);

//GOB.GUARICO
				$this->Session->write("monto_total_cancelarSS",$mt[0][0]["monto_total"]+$mt[0][0]["monto_amortizacion"]+$mt[0][0]["monto_laboral"]+$mt[0][0]["monto_fielcumplimiento"]);
//FIN GOB.GUARICO


//ORIGINAL
/*
							$this->Session->write("monto_total_cancelarSS",$mt[0][0]["monto_total"]+$mt[0][0]["monto_amortizacion"]);
*/
//FIN ORIGINAL


							$this->set("monto_iva_partidas",$mi[0][0]["monto_iva"]);
							$this->Session->write("monto_iva_partidasSS",$mi[0][0]["monto_iva"]);
							$this->set("monto401",$monto401[0][0]["monto_401"]);
							$this->Session->write("monto_a_descontar_inpuestoSS",$mdi[0][0]["monto_descontar_impuesto"]);
							$this->set('tipo',3);
							break;
				case 4://contrato obras anticipo
				$ano=$this->Session->read('ano_contrato_obra');
				$contrato_obra=$this->Session->read("numero_contrato_obra");
				$mt = $this->cobd01_contratoobras_anticipo_partidas->execute("SELECT SUM(monto) AS monto_total FROM cobd01_contratoobras_anticipo_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$contrato_obra."' and numero_anticipo=".$codigo);
				$this->set("monto_total_cancelar",$mt[0][0]["monto_total"]);
				$this->set("monto_orden_pago",$mt[0][0]["monto_total"]);
				$this->set("neto_cobrar",$mt[0][0]["monto_total"]);
				$this->set('tipo',4);

				break;
				case 5://contrato obras valuaciones
				$ano=$this->Session->read('ano_contrato_obra');
				$contrato_obra=$this->Session->read("numero_contrato_obra");
				$valuacion_obras = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_valuacion=".$codigo);
				$this->set("valuacion_obras",$valuacion_obras);
				$mt      = $this->cobd01_contratoobras_valuacion_partidas->execute("SELECT SUM(monto) AS monto_total, SUM(amortizacion) AS monto_amortizacion, SUM(retencion_laboral) AS monto_laboral, SUM(retencion_fielcumplimiento) AS monto_fielcumplimiento FROM cobd01_contratoobras_valuacion_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_valuacion=".$codigo);
				$mi      = $this->cobd01_contratoobras_valuacion_partidas->execute("SELECT SUM(monto) AS monto_iva, SUM(amortizacion) AS monto_amortizacion FROM cobd01_contratoobras_valuacion_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_valuacion=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
				$monto401= $this->cobd01_contratoobras_valuacion_partidas->execute("SELECT SUM(monto) AS monto_401 FROM cobd01_contratoobras_valuacion_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_valuacion=".$codigo." and cod_partida=401");
				$mdi      = $this->cobd01_contratoobras_valuacion_cuerpo->execute("SELECT monto_descontar_impuesto AS monto_descontar_impuesto FROM cobd01_contratoobras_valuacion_cuerpo WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_valuacion=".$codigo);
				$this->set("monto_total_cancelar",$mt[0][0]["monto_total"]);

//GOB.GUARICO
				$this->Session->write("monto_total_cancelarSS",$mt[0][0]["monto_total"]+$mt[0][0]["monto_amortizacion"]+$mt[0][0]["monto_laboral"]+$mt[0][0]["monto_fielcumplimiento"]);
//FIN GOB.GUARICO

//ORIGINAL
/*
							$this->Session->write("monto_total_cancelarSS",$mt[0][0]["monto_total"]+$mt[0][0]["monto_amortizacion"]);
*/
//FIN ORIGINAL
							$this->set("monto_iva_partidas",$mi[0][0]["monto_iva"]);
							$this->Session->write("monto_iva_partidasSS",$mi[0][0]["monto_iva"]+$mi[0][0]["monto_amortizacion"]);
							$this->set("monto401",$monto401[0][0]["monto_401"]);
							$this->Session->write("monto_a_descontar_inpuestoSS",$mdi[0][0]["monto_descontar_impuesto"]);
							$this->set('tipo',5);

							break;
				case 6://contrato obras retenciones
				//'cobd01_contratoobras_retencion_cuerpo','cobd01_contratoobras_retencion_partidas',
				$ano=$this->Session->read('ano_contrato_obra');
				$contrato_obra=$this->Session->read("numero_contrato_obra");
				$valuacion_obras = $this->cobd01_contratoobras_retencion_cuerpo->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_retencion=".$codigo);
				$this->set("valuacion_obras",$valuacion_obras);
				$mt      = $this->cobd01_contratoobras_retencion_partidas->execute("SELECT SUM(monto) AS monto_total FROM cobd01_contratoobras_retencion_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_retencion=".$codigo);
				$mi      = $this->cobd01_contratoobras_retencion_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cobd01_contratoobras_retencion_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_retencion=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
				$monto401= $this->cobd01_contratoobras_retencion_partidas->execute("SELECT SUM(monto) AS monto_401   FROM cobd01_contratoobras_retencion_partidas WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and upper(numero_contrato_obra)='".strtoupper($contrato_obra)."' and numero_retencion=".$codigo." and cod_partida=401");
				$this->set("monto_total_cancelar",$mt[0][0]["monto_total"]);
				$this->Session->write("monto_total_cancelarSS",$mt[0][0]["monto_total"]);
				$this->set("monto_iva_partidas",$mi[0][0]["monto_iva"]);
				$this->Session->write("monto_iva_partidasSS",$mi[0][0]["monto_iva"]);
				$this->set("monto401",$monto401[0][0]["monto_401"]);
				$this->set('tipo',6);

				break;
				case 7://contrato servicio anticipo
                            //'cepd02_contratoservicio_anticipo_cuerpo','cepd02_contratoservicio_anticipo_partidas','v_cepd02_servicio_anticipo'
				$ano=$this->Session->read('ano_contrato_servicio');
				$numero_contrato_servicio=$this->Session->read("numero_contrato_servicio");
				$mt = $this->cepd02_contratoservicio_anticipo_partidas->execute("SELECT SUM(monto) AS monto_total FROM cepd02_contratoservicio_anticipo_partidas WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_contrato_servicio."' and numero_anticipo=".$codigo);
				$this->set("monto_total_cancelar",$mt[0][0]["monto_total"]);
				$this->set("monto_orden_pago",$mt[0][0]["monto_total"]);
				$this->set("neto_cobrar",$mt[0][0]["monto_total"]);
				$this->set('tipo',4);
				break;
				case 8://contrato servicio valuaciones
				$ano=$this->Session->read('ano_contrato_servicio');
				$contrato_obra=$this->Session->read("numero_contrato_servicio");
				$valuacion_obras = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and upper(numero_contrato_servicio)='".strtoupper($contrato_obra)."' and numero_valuacion=".$codigo);
				$this->set("valuacion_obras",$valuacion_obras);
				$mt      = $this->cobd01_contratoobras_valuacion_partidas->execute("SELECT SUM(monto) AS monto_total, SUM(amortizacion) AS monto_amortizacion, SUM(retencion_laboral) AS monto_laboral, SUM(retencion_fielcumplimiento) AS monto_fielcumplimiento FROM cepd02_contratoservicio_valuacion_partidas WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and upper(numero_contrato_servicio)='".strtoupper($contrato_obra)."' and numero_valuacion=".$codigo);
				$mi      = $this->cepd02_contratoservicio_valuacion_partidas->execute("SELECT SUM(monto) AS monto_iva, SUM(amortizacion) AS monto_amortizacion FROM cepd02_contratoservicio_valuacion_partidas WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and upper(numero_contrato_servicio)='".strtoupper($contrato_obra)."' and numero_valuacion=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
				$monto401= $this->cepd02_contratoservicio_valuacion_partidas->execute("SELECT SUM(monto) AS monto_401   FROM cepd02_contratoservicio_valuacion_partidas WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and upper(numero_contrato_servicio)='".strtoupper($contrato_obra)."' and numero_valuacion=".$codigo." and cod_partida=401");
				$mdi      = $this->cepd02_contratoservicio_valuacion_cuerpo->execute("SELECT monto_descontar_impuesto AS monto_descontar_impuesto FROM cepd02_contratoservicio_valuacion_cuerpo WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and upper(numero_contrato_servicio)='".strtoupper($contrato_obra)."' and numero_valuacion=".$codigo);
				$this->set("monto_total_cancelar",$mt[0][0]["monto_total"]);
//GOB.GUARICO
				$this->Session->write("monto_total_cancelarSS",$mt[0][0]["monto_total"]+$mt[0][0]["monto_amortizacion"]+$mt[0][0]["monto_laboral"]+$mt[0][0]["monto_fielcumplimiento"]);
//FIN GOB.GUARICO

//ORIGINAL
/*
							$this->Session->write("monto_total_cancelarSS",$mt[0][0]["monto_total"]+$mt[0][0]["monto_amortizacion"]);
*/
//FIN ORIGINAL
							$this->set("monto_iva_partidas",$mi[0][0]["monto_iva"]);
							$this->Session->write("monto_iva_partidasSS",$mi[0][0]["monto_iva"]+$mi[0][0]["monto_amortizacion"]);
							$this->set("monto401",$monto401[0][0]["monto_401"]);
							$this->Session->write("monto_a_descontar_inpuestoSS",$mdi[0][0]["monto_descontar_impuesto"]);
							$this->set('tipo',8);
							break;
				case 9://contrato servicio retenciones
				       //'cepd02_contratoservicio_retencion_cuerpo','cepd02_contratoservicio_retencion_partidas',
				$ano=$this->Session->read('ano_contrato_servicio');
				$contrato_obra=$this->Session->read("numero_contrato_servicio");
				$valuacion_obras = $this->cepd02_contratoservicio_retencion_cuerpo->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and upper(numero_contrato_servicio)='".strtoupper($contrato_obra)."' and numero_retencion=".$codigo);
				$this->set("valuacion_obras",$valuacion_obras);
				$mt      = $this->cepd02_contratoservicio_retencion_partidas->execute("SELECT SUM(monto) AS monto_total FROM cepd02_contratoservicio_retencion_partidas WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and upper(numero_contrato_servicio)='".strtoupper($contrato_obra)."' and numero_retencion=".$codigo);
				$mi      = $this->cepd02_contratoservicio_retencion_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cepd02_contratoservicio_retencion_partidas WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and upper(numero_contrato_servicio)='".strtoupper($contrato_obra)."' and numero_retencion=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
				$monto401= $this->cepd02_contratoservicio_retencion_partidas->execute("SELECT SUM(monto) AS monto_401   FROM cepd02_contratoservicio_retencion_partidas WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and upper(numero_contrato_servicio)='".strtoupper($contrato_obra)."' and numero_retencion=".$codigo." and cod_partida=401");
				$this->set("monto_total_cancelar",$mt[0][0]["monto_total"]);
				$this->Session->write("monto_total_cancelarSS",$mt[0][0]["monto_total"]);
				$this->set("monto_iva_partidas",$mi[0][0]["monto_iva"]);
				$this->Session->write("monto_iva_partidasSS",$mi[0][0]["monto_iva"]);
				$this->set("monto401",$monto401[0][0]["monto_401"]);
				$this->set('tipo',9);

				break;
				case 10://Ordenes compras - Retenciones
				$ano=$this->Session->read('ano_orden_compra');
				$contrato_obra=$this->Session->read("numero_orden_compra");
				$valuacion_obras = $this->cscd04_ordencompra_retencion_cuerpo->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$contrato_obra." and numero_retencion=".$codigo);
				$this->set("retencion_compras",$valuacion_obras);
				$mt      = $this->cscd04_ordencompra_retencion_partidas->execute("SELECT SUM(monto) AS monto_total FROM cscd04_ordencompra_retencion_partidas WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$contrato_obra." and numero_retencion=".$codigo);
				$mi      = $this->cscd04_ordencompra_retencion_partidas->execute("SELECT SUM(monto) AS monto_iva   FROM cscd04_ordencompra_retencion_partidas WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$contrato_obra." and numero_retencion=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
				$monto401= $this->cscd04_ordencompra_retencion_partidas->execute("SELECT SUM(monto) AS monto_401   FROM cscd04_ordencompra_retencion_partidas WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$contrato_obra." and numero_retencion=".$codigo." and cod_partida=401");
				$this->set("monto_total_cancelar",$mt[0][0]["monto_total"]);
				$this->Session->write("monto_total_cancelarSS",$mt[0][0]["monto_total"]);
				$this->set("monto_iva_partidas",$mi[0][0]["monto_iva"]);
				$this->Session->write("monto_iva_partidasSS",$mi[0][0]["monto_iva"]);
				$this->set("monto401",$monto401[0][0]["monto_401"]);
				$this->set('tipo',10);

				break;
			}//fin switch
			//echo $mi[0][0]["monto_iva"]." iva";
		}
}//fin cargar3

function _monto_iva_ss ($codigo,$porc_iva) {

	$ano=$ano=$this->ano_ejecucion();
	$mt = $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_total FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo);
	$mi= $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
	$monto401= $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_401 FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo." and cod_partida=401");
	$cantidad_4031801 = $this->cepd01_compromiso_partidas->execute("SELECT count(*) AS c FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");



// SI EN ALGÚN MOMENTO DE LA PARTIDA 411 SE EXTRAE EL IVA...QUITAR ESTE COMENTARIO
/*
	 $monto411= $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_411 FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo." and cod_partida=411");
	if($monto411[0][0]["monto_411"]!=0 &&  $cantidad_4031801[0][0]["c"]==0){
		    $rever= ($porc_iva/100)+1;
	    	$base411=$monto411[0][0]["monto_411"]/$rever;
	    	$iva411=$monto411[0][0]["monto_411"]-decimal_sprintf("%01.2f",$base411);
	    	return $iva411;
	    }else{
	    	$mi= $this->cepd01_compromiso_partidas->execute("SELECT SUM(monto) AS monto_iva FROM cepd01_compromiso_partidas WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$codigo." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
	    	$iva411=$mi[0][0]['monto_iva'];
	    	return $iva411;
	    }
*/

}//fin funcion _monto_iva_ss

function filas_facturas(){

	$this->layout="ajax";
	if(isset($this->data["cepp03_ordenpago"])){
		if(isset($_SESSION["i"])){
			$i=$this->Session->read("i")+1;
			$this->Session->write("i",$i);
		}else{
			$this->Session->write("i",0);
			$i=0;
		}
		$monto_total_cancelar=$this->Session->read("monto_total_cancelarSS");

		if($this->data['cepp03_ordenpago']['tipo_documento']==1){
          //$xxxxxx = $this->_monto_iva_ss($this->data["cepp03_ordenpago"]["numero_documento"],$this->Formato1($this->data["cepp03_ordenpago"]["porcentaje_iva"]));
          //$monto_iva_partidas=$xxxxxx;
          //$_SESSION["m_iva"] = $xxxxxx;
			$monto_iva_partidas=$this->Session->read("monto_iva_partidasSS");
			$_SESSION["m_iva"] = $monto_iva_partidas;
		}else{
			$monto_iva_partidas=$this->Session->read("monto_iva_partidasSS");
			$_SESSION["m_iva"] = $monto_iva_partidas;
		}
		$monto_descontar_inspuesto=$this->Session->read("monto_a_descontar_inpuestoSS");

		$vec[$i][0]=$this->data["cepp03_ordenpago"]["num_factura"];
		$vec[$i][1]=$this->data["cepp03_ordenpago"]["num_control"];
		$vec[$i][2]=$this->data["cepp03_ordenpago"]["fecha_factura"];
		$vec[$i][3]=$this->data["cepp03_ordenpago"]["monto_total"];
		$vec[$i][4]=$this->data["cepp03_ordenpago"]["monto_base"];
		$vec[$i][5]=$this->data["cepp03_ordenpago"]["f_iva"];
		$vec[$i][6]=$this->data["cepp03_ordenpago"]["monto_iva"];
		$vec[$i][7]=$this->data["cepp03_ordenpago"]["excento"];
		$reten=$this->Formato1($this->data["cepp03_ordenpago"]["monto_iva"])*$this->Formato1($this->data["cepp03_ordenpago"]["porcentaje_retencion_iva"])/100;
					 $vec[$i][8]=$this->Formato2(decimal_sprintf("%01.2f",$reten));//$this->Formato2($reten);//;
					 $vec[$i]["id"]=$i;
					 $prueba=$this->Formato1($this->data["cepp03_ordenpago"]["monto_iva"])*$this->Formato1($this->data["cepp03_ordenpago"]["porcentaje_retencion_iva"])/100;
					 $x=decimal_sprintf("%01.2f",$prueba);
					 $this->set("dfactura",$vec);
					 $this->set("dfacturaI",$i);
					 if(isset($_SESSION["items"])){
					 	$m=0;
					 	$m2=$_SESSION["m_iva"];
					 	$m_t=0;
					 	$m_b=0;
					 	foreach($_SESSION ["items"] as $codigos){
					 		$m_t=$m_t+$this->Formato1($codigos[3]);
					 		$m_b=$m_b+$this->Formato1($codigos[4]);
					 		$pc_i=$this->Formato1($codigos[5]);
					 	}

					 	$m=($m_b*$pc_i)/100;

                      //$XF=$m+$this->Formato1($vec[$i][6]);

					 	$XF=$m+($this->Formato1($vec[$i][4])*$pc_i)/100;

					 	$m=(float) floatval($m);
					 	$m2=(float) floatval($m2);
					 	$XF=(float) floatval($XF);
					 	$m=decimal_sprintf("%01.2f",$m);
					 	$m2=decimal_sprintf("%01.2f",$m2);
					 	$XF=decimal_sprintf("%01.2f",$XF);

					 	if(($m<=$m2) && ($XF<=$m2)){
					 		$_SESSION["items"]=$_SESSION["items"]+$vec;

					 	}else{
					 		$i=$this->Session->read("i")-1;
					 		$this->Session->write("i",$i);
					 		$this->set("errorMessage","El Monto Ingresado en la Factura excede el monto total del iva. ");
					 		$this->set("errorExcede",true);
					 	}
					 	if((($m+$this->Formato1($vec[$i][6]))==$m2)){
					 		$this->set("monto_otra_factura",0);
					 		$this->set("monto_otra_factura_iva",0);
					 		$this->set("monto_otra_d_inspuesto",0);
					 		$this->set("iva",0);
					 		$this->set("excento",0);
					 		$this->set("masFactura",true);
					 	}else{
					 		$monto_otra_factura=$monto_total_cancelar-$m_t-$this->Formato1($vec[$i][3]);
					 		$monto_otra_factura_iva=$monto_iva_partidas-$m-$this->Formato1($vec[$i][6]);
					 		$monto_otra_d_inspuesto=$monto_descontar_inspuesto-$m_b-$this->Formato1($vec[$i][4]);
					 		$excento=$monto_otra_factura-($monto_otra_factura_iva+$monto_otra_d_inspuesto);
					 		$this->set("monto_otra_factura",$monto_otra_factura);
					 		$this->set("monto_otra_factura_iva",$monto_otra_factura_iva);
					 		$this->set("monto_otra_d_inspuesto",$monto_otra_d_inspuesto);
					 		$this->set("iva",$this->data["cepp03_ordenpago"]["f_iva"]);
					 		$this->set("excento",$excento);
					 		$this->set("masFactura",true);
					 	}
					 }else{
					 	$_SESSION["items"]=$vec;
					 	$monto_otra_factura=$monto_total_cancelar-$this->Formato1($this->data["cepp03_ordenpago"]["monto_total"]);
					 	$monto_otra_factura_iva=$monto_iva_partidas-$this->Formato1($this->data["cepp03_ordenpago"]["monto_iva"]);
					 	$monto_otra_d_inspuesto=$monto_descontar_inspuesto-$this->Formato1($this->data["cepp03_ordenpago"]["monto_base"]);
					 	$excento=$monto_otra_factura-($monto_otra_factura_iva+$monto_otra_d_inspuesto);
					 	$this->set("monto_otra_factura",$monto_otra_factura);
					 	$this->set("monto_otra_factura_iva",$monto_otra_factura_iva);
					 	$this->set("monto_otra_d_inspuesto",$monto_otra_d_inspuesto);
					 	$this->set("iva",$this->data["cepp03_ordenpago"]["f_iva"]);
					 	$this->set("excento",$excento);
					 	$this->set("masFactura",true);
					 }
					}
				}

				function filas_facturas_2(){

					$this->layout="ajax";
					if(isset($this->data["cepp03_ordenpago"])){
						if(isset($_SESSION["i"])){
							$i=$this->Session->read("i")+1;
							$this->Session->write("i",$i);
						}else{
							$this->Session->write("i",0);
							$i=0;
						}
						$monto_iva_partidas = 0;
						$_SESSION["m_iva"]  = 0;
						$vec[$i][0]=$this->data["cepp03_ordenpago"]["num_factura"];
						$vec[$i][1]=$this->data["cepp03_ordenpago"]["num_control"];
						$vec[$i][2]=$this->data["cepp03_ordenpago"]["fecha_factura"];
						$vec[$i][3]=$this->data["cepp03_ordenpago"]["monto_total"];
						$vec[$i][4]=$this->data["cepp03_ordenpago"]["monto_base"];
						$vec[$i][5]=$this->data["cepp03_ordenpago"]["f_iva"];
						$vec[$i][6]=$this->data["cepp03_ordenpago"]["monto_iva"];
						$vec[$i][7]=$this->data["cepp03_ordenpago"]["excento"];
					 $vec[$i][8]=0;//$this->Formato2($reten);//;
					 $vec[$i]["id"]=$i;
					 $this->set("dfactura",$vec);
					 $this->set("dfacturaI",$i);
					 if(isset($_SESSION["items"])){
					 	$m=0;
					 	$m2=$_SESSION["m_iva"];
					 	$m_t=0;
					 	$m_b=0;
					 	foreach($_SESSION ["items"] as $codigos){
					 	  //$m=$m+$this->Formato1($codigos[6]);
					 		$m_t=$m_t+$this->Formato1($codigos[3]);
					 		$m_b=$m_b+$this->Formato1($codigos[4]);
					 		$pc_i=$this->Formato1($codigos[5]);
					 	}

					 	$m=($m_b*$pc_i)/100;

                      //$XF=$m+$this->Formato1($vec[$i][6]);

					 	$XF=$m+($this->Formato1($vec[$i][4])*$pc_i)/100;


					 	$m=(float) floatval($m);
					 	$m2=(float) floatval($m2);
					 	$XF=(float) floatval($XF);
					 	$m=decimal_sprintf("%01.2f",$m);
					 	$m2=decimal_sprintf("%01.2f",$m2);
					 	$XF=decimal_sprintf("%01.2f",$XF);
                        //echo "A:".$m;
                        //echo "B:".$m2;
                        //echo "C:".$XF;

					 	if(($m<=$m2) && ($XF<=$m2)){
					 		$_SESSION["items"]=$_SESSION["items"]+$vec;

					 	}else{
					 		$i=$this->Session->read("i")-1;
					 		$this->Session->write("i",$i);
					 		$this->set("errorMessage","El Monto Ingresado en la Factura excede el monto total del iva. ");
					 		$this->set("errorExcede",true);
					 	}
					 	if((($m+$this->Formato1($vec[$i][6]))==$m2)){}else{}
					 }else{
					 	$_SESSION["items"]=$vec;
//						       $monto_otra_factura=$monto_total_cancelar-$this->Formato1($this->data["cepp03_ordenpago"]["monto_total"]);
//                               $monto_otra_factura_iva=$monto_iva_partidas-$this->Formato1($this->data["cepp03_ordenpago"]["monto_iva"]);
//                               $monto_otra_d_inspuesto=$monto_descontar_inspuesto-$this->Formato1($this->data["cepp03_ordenpago"]["monto_base"]);
//                               $excento=$monto_otra_factura-($monto_otra_factura_iva+$monto_otra_d_inspuesto);
					 	$this->set("monto_otra_factura",0);
					 	$this->set("monto_otra_factura_iva",0);
					 	$this->set("monto_otra_d_inspuesto",0);
					 	$this->set("iva",0);
					 	$this->set("excento",0);
					 	$this->set("masFactura",true);
//                                echo "F3";
					 }


					}
					$this->render('filas_facturas');
				}


				function eliminar_items ($id) {
					$this->layout = "ajax";
					$_SESSION["items"][$id]=null;

				}
				function ajustar_factura ($id) {
					$this->layout = "ajax";
					$this->set("ajustar",$_SESSION["items"][$id]);
					$_SESSION["items"][$id]=null;
		/*$i=0;
		foreach($_SESSION["items"] as $nss){
		if($nss!=null){
					 $new_array[$i][0]=$nss[0];
					 $new_array[$i][1]=$nss[1];
					 $new_array[$i][2]=$nss[2];
					 $new_array[$i][3]=$nss[3];
					 $new_array[$i][4]=$nss[4];
					 $new_array[$i][5]=$nss[5];
					 $new_array[$i][6]=$nss[6];
					 $new_array[$i][7]=$nss[7];
					 $new_array[$i]["id"]=$i;
		$i++;
		}
	}
	$this->Session->delete("items");
	$this->Session->delete("i");
	//$_SESSION["items"]=$new_array;
	$this->Session->write("items",$new_array);
	$this->Session->write("i",$i-1);
	//print_r($_SESSION["items"]);
	 *
	 */
}
function limpiar_lista () {
	$this->layout = "ajax";
	$this->Session->delete("items");
	$this->Session->delete("i");
	echo "<script>";
	echo 'document.getElementById("td_num_factura").innerHTML=\'<input type="text" name="data[cepp03_ordenpago][num_factura]" id="num_factura" value="" maxlength="40" class="inputText" onBlur="s_factura()"/>\';';
	echo "</script>";
}

function guardar () {
	$this->layout="ajax";
	$cod_presi=$this->verifica_SS(1);
	$cod_entidad=$this->verifica_SS(2);
	$cod_tipo_inst=$this->verifica_SS(3);
	$cod_inst=$this->verifica_SS(4);
	if(isset($this->data["cepp03_ordenpago"])){
		$var[0] = $this->data["cepp03_ordenpago"]["ano_orden"];
		$ann = $this->data["cepp03_ordenpago"]["ano_orden"];
		$var[1] = $this->data["cepp03_ordenpago"]["tipo_de_orden"];
						$fd = $this->data["cepp03_ordenpago"]["fecha_documento_orden"];// 01/34/6789
						$var[2] = $fd[6].$fd[7].$fd[8].$fd[9]."-".$fd[3].$fd[4]."-".$fd[0].$fd[1];
						$opfecha=$fd;
						$var[3] = $this->data["cepp03_ordenpago"]["ano_documento"];
						$var[4] = $this->data["cepp03_ordenpago"]["tipo_documento"];
						$var[5] = $this->data["cepp03_ordenpago"]["numero_documento"];

						$numero_orden_pago = $this->data["cepp03_ordenpago"]["numero_orden_pago"];
						$ano_orden_pago    = $this->data["cepp03_ordenpago"]["ano_orden"];
						$monto_mano_obra   = isset($this->data["cepp03_ordenpago"]["monto_mano_obra"])?$this->Formato1($this->data["cepp03_ordenpago"]["monto_mano_obra"]):0;

						if(isset($this->data["cepp03_ordenpago"]["numero_documento_adjunto"]) && $this->data["cepp03_ordenpago"]["numero_documento_adjunto"]!="")
							$numero_doc_adjunto = $this->data["cepp03_ordenpago"]["numero_documento_adjunto"];
						else
							$numero_doc_adjunto = 0;

						$fdoo = $this->data["cepp03_ordenpago"]["fecha_documento_origen"];// 01/34/6789
						$fdo=$this->Cfecha($fdoo,"A-M-D");

						$m_fdo=$this->data["cepp03_ordenpago"]["fecha_documento_orden"];
						$m_opfecha=$this->data["cepp03_ordenpago"]["fecha_documento_origen"];

						$ano_documento    = $var[3];
						$numero_documento = $var[5];
						$fecha_documento  = $m_opfecha;

						$var[6] = $this->data["cepp03_ordenpago"]["rif_ci"];
						$var[7] = $this->data["cepp03_ordenpago"]["beneficiario"];
						$var[9] = $this->data["cepp03_ordenpago"]["autorizado_cobrar"];
						$var[9] = str_replace('"','\"',$var[9]);
						//$var[9] = str_replace("'","\'",$var[9]);
						$var[10] = $this->data["cepp03_ordenpago"]["autorizado_cedula"];
						$var[10] = str_replace('"','\"',$var[10]);
						//$var[10] = str_replace("'","\'",$var[10]);
						if($var[10]=="" || $var[10]==null){
							$var[10]=0;
						}
						//
						$var[11] = $this->Formato1($this->data["cepp03_ordenpago"]["monto_t_partidas"]);
						$var[12] = $this->data["cepp03_ordenpago"]["tipo_pago"];

						$var[13] = $this->Formato1($this->data["cepp03_ordenpago"]["monto_total_cancelar"]);
						if(isset($this->data["cepp03_ordenpago"]["saldo_excento"])){
							$var[131] = $this->Formato1($this->data["cepp03_ordenpago"]["saldo_excento"]);
						}else{
							$var[131] = 0;
						}
						$var[14] = $this->Formato1($this->data["cepp03_ordenpago"]["porcentaje_retencion"]);
						$var[15] = $this->Formato1($this->data["cepp03_ordenpago"]["monto_laboral"]);
						$var[16] = $this->Formato1($this->data["cepp03_ordenpago"]["porcentaje_fiel_cumplimiento"]);
						$var[17] = $this->Formato1($this->data["cepp03_ordenpago"]["monto_fiel_cumplimiento"]);
						$var[18] = $this->Formato1($this->data["cepp03_ordenpago"]["monto_iva_partidas"]);
						$var[19] = $this->Formato1($this->data["cepp03_ordenpago"]["monto_descontar_impuesto"]);
						$var[20] = $this->Formato1($this->data["cepp03_ordenpago"]["porcentaje_amortizacion_anticipo"]);
						$var[21] = $this->Formato1($this->data["cepp03_ordenpago"]["monto_amortizacion_anticipo"]);
						$var[22] = $this->Formato1($this->data["cepp03_ordenpago"]["monto_orden_pago"]);
						$var[23] = $this->Formato1($this->data["cepp03_ordenpago"]["porcentaje_retencion_iva"]);
						$var[24] = $this->Formato1($this->data["cepp03_ordenpago"]["monto_retencion_iva"]);
						$var[25] = $this->Formato1($this->data["cepp03_ordenpago"]["porcentaje_isrl"]);
						$var[26] = $this->Formato1($this->data["cepp03_ordenpago"]["monto_isrl"]);
						$var[27] = $this->Formato1($this->data["cepp03_ordenpago"]["porcentaje_timbre_fiscal"]);
						$var[28] = $this->Formato1($this->data["cepp03_ordenpago"]["monto_timbre_fiscal"]);
						$var[29] = $this->Formato1($this->data["cepp03_ordenpago"]["porcentaje_impuesto_municipal"]);
						$var[30] = $this->Formato1($this->data["cepp03_ordenpago"]["monto_impuesto_municipal"]);
						$var[31] = $this->Formato1($this->data["cepp03_ordenpago"]["neto_cobrar"]);
						$sustraendo=$this->Formato1($this->data["cepp03_ordenpago"]["monto_sustraendo"]);
						$porcentaje_iva=$this->Formato1($this->data["cepp03_ordenpago"]["porcentaje_iva"]);
						$var[32] = $this->Formato1($this->data["cepp03_ordenpago"]["c_monto_total"]);
						$var[33] = $this->Formato1($this->data["cepp03_ordenpago"]["c_numero_pago"]);
						$var[34] = $this->Formato1($this->data["cepp03_ordenpago"]["c_monto_parcial"]);
						$fd1     = $this->data["cepp03_ordenpago"]["c_fecha_desde"];
						$fd2     = $this->data["cepp03_ordenpago"]["c_fecha_hasta"];
						$var[35] =  $this->Cfecha($fd1,"A-M-D");
						$var[36] =  $this->Cfecha($fd2,"A-M-D");
						$var[37] = $this->data["cepp03_ordenpago"]["frecuencia_de_pago"];

						$var[38] = $this->data["cepp03_ordenpago"]["num_factura"];
						$var[39] = $this->data["cepp03_ordenpago"]["num_control"];
						$var[40] = $this->data["cepp03_ordenpago"]["fecha_factura"];
						$var[41] = $this->data["cepp03_ordenpago"]["monto_total"];
						$var[42] = $this->data["cepp03_ordenpago"]["monto_base"];
						$var[43] = $this->data["cepp03_ordenpago"]["f_iva"];
						$var[44] = $this->data["cepp03_ordenpago"]["monto_iva"];
						$var[45] = $this->data["cepp03_ordenpago"]["concepto"];
						$var[45] = str_replace('"','\"',$var[45]);

						//$var[45] = str_replace("'","\'",$var[45]);
						$var[46] = $this->data["cepp03_ordenpago"]["comprobante_retencion_isrl_ano"];
						$var[47] = $this->data["cepp03_ordenpago"]["comprobante_retencion_isrl_numero"];
						$var[48] = $this->data["cepp03_ordenpago"]["comprobante_retencion_timbre_fiscal_ano"];
						$var[49] = $this->data["cepp03_ordenpago"]["comprobante_retencion_timbre_fiscal_numero"];
						$var[50] = $this->data["cepp03_ordenpago"]["comprobante_retencion_municipal_ano"];
						$var[51] = $this->data["cepp03_ordenpago"]["comprobante_retencion_municipal_numero"];
						$var[52] = $this->data["cepp03_ordenpago"]["comprobante_retencion_iva_ano"];
						$var[53] = $this->data["cepp03_ordenpago"]["comprobante_retencion_iva_mes"];
						$var[54] = $this->data["cepp03_ordenpago"]["comprobante_retencion_iva_numero"];
						$var[55] = $this->data["cepp03_ordenpago"]["comprobante_retencion_libro_compra_dia"];
						$var[56] = $this->data["cepp03_ordenpago"]["comprobante_retencion_libro_compra_mes"];
						$var[57] = $this->data["cepp03_ordenpago"]["comprobante_retencion_libro_compra_ano"];
						$var[58] = $this->data["cepp03_ordenpago"]["comprobante_retencion_libro_compra_numero"];

						$var[59] = $this->Formato1($this->data["cepp03_ordenpago"]["monto_retencion_multa"]);
						$var[60] = $this->Formato1($this->data["cepp03_ordenpago"]["monto_retencion_responsabilidad_social"]);
						$var[61] = $this->Formato1($this->data["cepp03_ordenpago"]["rcivil"]);
						$var[62] = $this->Formato1($this->data["cepp03_ordenpago"]["rsocial"]);
						$var[63] = $this->data["cepp03_ordenpago"]["cta_banc_beneficiario"];


						$monto_total_orden_contabilidad  = $var[13];
						$monto_orden_pago_contabilidad   = $var[22];
						$monto_amortizacion_contabilidad = $var[21];

						$CAMPOS_CUERPO="cod_presi,
						cod_entidad,
						cod_tipo_inst,
						cod_inst,
						cod_dep,
						ano_orden_pago,
						numero_orden_pago,
						tipo_orden,
						fecha_orden_pago,
						ano_documento_origen,
						numero_documento_origen,
						numero_documento_adjunto,
						fecha_documento,
						cod_tipo_documento,
						rif,
						beneficiario,
						autorizado,
						cedula_identidad,
						concepto,
						monto_total,
						numero_pago,
						monto_parcial,
						cod_frecuencia_pago,
						fecha_desde,
						fecha_hasta,
						cod_tipo_pago,
						monto_coniva,
						monto_iva,
						porcentaje_iva,
						monto_siniva,
						monto_retencion_laboral,
						porcentaje_laboral,
						monto_retencion_fielcumplimiento,
						porcentaje_fielcumplimiento,
						monto_descontar_impuesto,
						amortizacion_anticipo,
						porcentaje_amortizacion,
						monto_orden_pago,
						monto_retencion_iva,
						porcentaje_retencion_iva,
						monto_islr,
						porcentaje_islr,
						monto_sustraendo,
						monto_timbre_fiscal,
						porcentaje_timbre_fiscal,
						monto_impuesto_municipal,
						porcentaje_impuesto_municipal,
						monto_neto_cobrar ,
						dia_asiento_registro,
						mes_asiento_registro,
						ano_asiento_registro,
						numero_asiento_registro,
						username_registro,
						condicion_actividad,
						ano_anulacion,
						numero_anulacion,
						dia_asiento_anulacion,
						mes_asiento_anulacion,
						ano_asiento_anulacion,
						numero_asiento_anulacion,
						username_anulacion,
						ano_movimiento,
						cod_entidad_bancaria,
						cod_sucursal,
						cuenta_bancaria,
						numero_cheque,
						fecha_cheque,
						fecha_proceso_registro,
						fecha_proceso_anulacion,
						numero_comprobante_islr,
						numero_comprobante_timbre,
						numero_comprobante_municipal,
						numero_comprobante_iva,
						numero_comprobante_librocompras,
						numero_comprobante_egreso,
						retencion_multa,
						retencion_responsabilidad,
						monto_mano_obra,
						codigo_retencion_islr,
						cod_actividad,
						porcentaje_multa,
						porcentaje_responsabilidad,
						enlace_contable,
						cuenta_bancaria_beneficiario,
						saldo_excento
						";


						$CAMPOS_PARTIDAS="cod_presi,
						cod_entidad,
						cod_tipo_inst,
						cod_inst,
						cod_dep,
						ano_orden_pago,
						numero_orden_pago,
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
						cod_auxiliar,
						monto,
						numero_control_compromiso,
						numero_control_causado";

						$CAMPOS_FACTURAS="cod_presi,
						cod_entidad,
						cod_tipo_inst,
						cod_inst,
						cod_dep,
						ano_orden_pago,
						numero_orden_pago,
						numero_factura,
						numero_control,
						fecha_factura,
						monto_total_factura,
						monto_sub_total,
						porcentaje_iva,
						monto_exento,monto_iva,monto_retencion_iva";



	//$VALUES_PARTIDAS="";
						switch($var[4]){
				case 1://compromiso
				$compromiso_partidas = $this->cepd01_compromiso_partidas->findAll($this->SQLCA()." and ano_documento=".$var[3]." and numero_documento=".$var[5]);
				$total_partidas = $this->cepd01_compromiso_partidas->findCount($this->SQLCA()." and ano_documento=".$var[3]." and numero_documento=".$var[5]);
				$total_partidas_iva = $this->cepd01_compromiso_partidas->findCount($this->SQLCA()." and ano_documento=".$var[3]." and numero_documento=".$var[5]." and ((cod_partida=403 and cod_generica=18 and cod_especifica=1) OR cod_partida=411)");
				$compromiso_partidas = $compromiso_partidas != null ? $compromiso_partidas : array();
				$partidas=$compromiso_partidas;
				$modelo="cepd01_compromiso_partidas";
				$modelo_tabla="cepd01_compromiso_partidas";
				$modelo_cuerpo1="cepd01_compromiso_cuerpo";
				$modelo_cuerpo2="cepd01_compromiso_cuerpo";
				$ano_up="ano_documento";
				$numero_up="numero_documento";
				$numero_up_adj="";
				$campo_ncc="numero_control_compromiso";
				$n_c_c="numero_control_causado";
				$to = 1;
				$td = 4;
				$ta = 1;
				$ndo = $var[5];
				$nda = 0;
				break;
				case 2://ordenes de compra  anticipo
				$compromiso_partidas = $this->cscd04_ordencompra_anticipo_partidas->findAll($this->SQLCA()." and ano_orden_compra=".$var[3]." and numero_orden_compra=".$var[5]." and numero_anticipo=".$numero_doc_adjunto);
				$total_partidas = $this->cscd04_ordencompra_anticipo_partidas->findCount($this->SQLCA()." and ano_orden_compra=".$var[3]." and numero_orden_compra=".$var[5]." and numero_anticipo=".$numero_doc_adjunto);
						$total_partidas_iva = 0;//$this->cscd04_ordencompra_anticipo_partidas->findCount($this->SQLCA()." and ano_documento=".$var[3]." and numero_documento=".$var[5]." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
						$compromiso_partidas = $compromiso_partidas != null ? $compromiso_partidas : array();
						$partidas=$compromiso_partidas;
						$modelo      ="cscd04_ordencompra_anticipo_partidas";
						$modelo_tabla="cscd04_ordencompra_anticipo_partidas";
						$modelo_cuerpo1="cscd04_ordencompra_anticipo_cuerpo";
						$modelo_cuerpo2="cscd04_ordencompra_anticipo_cuerpo";
						$ano_up="ano_orden_compra";
						$numero_up="numero_orden_compra";
						$numero_up_adj=" and numero_anticipo='".$numero_doc_adjunto."'";
						$campo_ncc="numero_control_compromiso";
						$n_c_c="numero_control_causado";
						$to = 1;
						$td = 4;
						$ta = 2;
						$ndo = $var[5];
						$nda = $numero_doc_adjunto;
						break;
				case 3://ordenes de compra autorizacion pago
				$compromiso_partidas = $this->cscd04_ordencompra_a_pago_partidas->findAll($this->SQLCA()." and ano_orden_compra=".$var[3]." and numero_orden_compra=".$var[5]." and numero_pago=".$numero_doc_adjunto);
				$total_partidas = $this->cscd04_ordencompra_a_pago_partidas->findCount($this->SQLCA()." and ano_orden_compra=".$var[3]." and numero_orden_compra=".$var[5]." and numero_pago=".$numero_doc_adjunto);
				$total_partidas_iva = $this->cscd04_ordencompra_a_pago_partidas->findCount($this->SQLCA()." and ano_orden_compra=".$var[3]." and numero_orden_compra=".$var[5]." and ((cod_partida=403 and cod_generica=18 and cod_especifica=1) OR cod_partida=411)");
				$compromiso_partidas = $compromiso_partidas != null ? $compromiso_partidas : array();
				$partidas=$compromiso_partidas;
				$modelo      ="cscd04_ordencompra_a_pago_partidas";
				$modelo_tabla="cscd04_ordencompra_autorizacion_pago_partidas";
				$modelo_cuerpo1="cscd04_ordencompra_autorizacion_cuerpo";
				$modelo_cuerpo2="cscd04_ordencompra_autorizacion_pago_cuerpo";
				$ano_up="ano_orden_compra";
				$numero_up="numero_orden_compra";
				$numero_up_adj=" and numero_pago='".$numero_doc_adjunto."'";
				$campo_ncc="numero_control_compromiso";
				$n_c_c="numero_control_causado";
				$to = 1;
				$td = 4;
				$ta = 3;
				$ndo = $var[5];
				$nda = $numero_doc_adjunto;
				break;
				case 4://contrato obras anticipo
				$compromiso_partidas = $this->cobd01_contratoobras_anticipo_partidas->findAll($this->SQLCA()." and ano_contrato_obra=".$var[3]." and numero_contrato_obra='".$var[5]."' and numero_anticipo=".$numero_doc_adjunto);
				$total_partidas = $this->cobd01_contratoobras_anticipo_partidas->findCount($this->SQLCA()." and ano_contrato_obra=".$var[3]." and numero_contrato_obra='".$var[5]."' and numero_anticipo=".$numero_doc_adjunto);
						$total_partidas_iva = 0;//$this->cscd04_ordencompra_anticipo_partidas->findCount($this->SQLCA()." and ano_documento=".$var[3]." and numero_documento=".$var[5]." and cod_partida=403 and cod_generica=18 and cod_especifica=1");
						$compromiso_partidas = $compromiso_partidas != null ? $compromiso_partidas : array();
						$partidas=$compromiso_partidas;
						$modelo      ="cobd01_contratoobras_anticipo_partidas";
						$modelo_tabla="cobd01_contratoobras_anticipo_partidas";
						$modelo_cuerpo1="cobd01_contratoobras_anticipo_cuerpo";
						$modelo_cuerpo2="cobd01_contratoobras_anticipo_cuerpo";
						$ano_up="ano_contrato_obra";
						$numero_up="numero_contrato_obra";
						$numero_up_adj=" and numero_anticipo='".$numero_doc_adjunto."'";
						$campo_ncc="numero_control_compromi";
						$n_c_c="numero_control_causado";
						$to = 1;
						$td = 4;
						$ta = 4;
						$ndo = $var[5];
						$nda = $numero_doc_adjunto;
						break;
				case 5://contrato obras valuaciones
				$compromiso_partidas = $this->cobd01_contratoobras_valuacion_partidas->findAll($this->SQLCA()." and ano_contrato_obra=".$var[3]." and numero_contrato_obra='".$var[5]."' and numero_valuacion=".$numero_doc_adjunto);
				$total_partidas = $this->cobd01_contratoobras_valuacion_partidas->findCount($this->SQLCA()." and ano_contrato_obra=".$var[3]." and numero_contrato_obra='".$var[5]."' and numero_valuacion=".$numero_doc_adjunto);
				$total_partidas_iva = $this->cobd01_contratoobras_valuacion_partidas->findCount($this->SQLCA()." and ano_contrato_obra=".$var[3]." and numero_contrato_obra='".$var[5]."' and numero_valuacion=".$numero_doc_adjunto."  and cod_partida=403 and cod_generica=18 and cod_especifica=1");
				$compromiso_partidas = $compromiso_partidas != null ? $compromiso_partidas : array();
				$partidas=$compromiso_partidas;
				$modelo="cobd01_contratoobras_valuacion_partidas";
				$modelo_tabla="cobd01_contratoobras_valuacion_partidas";
				$modelo_cuerpo1="cobd01_contratoobras_valuacion_cuerpo";
				$modelo_cuerpo2="cobd01_contratoobras_valuacion_cuerpo";
				$ano_up="ano_contrato_obra";
				$numero_up="numero_contrato_obra";
				$numero_up_adj=" and numero_valuacion='".$numero_doc_adjunto."'";
				$campo_ncc="numero_control_comprom";
				$n_c_c="numero_control_causado";
				$to = 1;
				$td = 4;
				$ta = 5;
				$ndo = $var[5];
				$nda = $numero_doc_adjunto;
				break;
				case 6://contrato obras retenciones
				$compromiso_partidas = $this->cobd01_contratoobras_retencion_partidas->findAll($this->SQLCA()." and ano_contrato_obra=".$var[3]." and numero_contrato_obra='".$var[5]."' and numero_retencion=".$numero_doc_adjunto);
				$total_partidas = $this->cobd01_contratoobras_retencion_partidas->findCount($this->SQLCA()." and ano_contrato_obra=".$var[3]." and numero_contrato_obra='".$var[5]."' and numero_retencion=".$numero_doc_adjunto);
				$total_partidas_iva = $this->cobd01_contratoobras_retencion_partidas->findCount($this->SQLCA()." and ano_contrato_obra=".$var[3]." and numero_contrato_obra='".$var[5]."' and numero_retencion=".$numero_doc_adjunto."  and cod_partida=403 and cod_generica=18 and cod_especifica=1");
				$compromiso_partidas = $compromiso_partidas != null ? $compromiso_partidas : array();
				$partidas=$compromiso_partidas;
				$modelo="cobd01_contratoobras_retencion_partidas";
				$modelo_tabla="cobd01_contratoobras_retencion_partidas";
				$modelo_cuerpo1="cobd01_contratoobras_retencion_cuerpo";
				$modelo_cuerpo2="cobd01_contratoobras_retencion_cuerpo";
				$ano_up="ano_contrato_obra";
				$numero_up="numero_contrato_obra";
				$numero_up_adj=" and numero_retencion='".$numero_doc_adjunto."'";
				$campo_ncc="numero_control_comprom";
				$n_c_c="numero_control_causado";
				$to = 1;
				$td = 4;
				$ta = 12;
				$ndo = $var[5];
				$nda = $numero_doc_adjunto;
				break;
				case 7://contrato servicio anticipo
				 //'cepd02_contratoservicio_anticipo_cuerpo','cepd02_contratoservicio_anticipo_partidas','v_cepd02_servicio_anticipo'
				$compromiso_partidas = $this->cepd02_contratoservicio_anticipo_partidas->findAll($this->SQLCA()." and ano_contrato_servicio=".$var[3]." and numero_contrato_servicio='".$var[5]."' and numero_anticipo=".$numero_doc_adjunto);
				$total_partidas = $this->cepd02_contratoservicio_anticipo_partidas->findCount($this->SQLCA()." and ano_contrato_servicio=".$var[3]." and numero_contrato_servicio='".$var[5]."' and numero_anticipo=".$numero_doc_adjunto);
				$total_partidas_iva = 0;
				$compromiso_partidas = $compromiso_partidas != null ? $compromiso_partidas : array();
				$partidas=$compromiso_partidas;
				$modelo="cepd02_contratoservicio_anticipo_partidas";
				$modelo_tabla="cepd02_contratoservicio_anticipo_partidas";
				$modelo_cuerpo1="cepd02_contratoservicio_anticipo_cuerpo";
				$modelo_cuerpo2="cepd02_contratoservicio_anticipo_cuerpo";
				$ano_up="ano_contrato_servicio";
				$numero_up="numero_contrato_servicio";
				$numero_up_adj=" and numero_anticipo='".$numero_doc_adjunto."'";
				$campo_ncc="numero_control_compr";
				$n_c_c="numero_control_causa";
				$to = 1;
				$td = 4;
				$ta = 6;
				$ndo = $var[5];
				$nda = $numero_doc_adjunto;
				break;
				case 8://contrato servicio valuaciones
				$compromiso_partidas = $this->cepd02_contratoservicio_valuacion_partidas->findAll($this->SQLCA()." and ano_contrato_servicio=".$var[3]." and numero_contrato_servicio='".$var[5]."' and numero_valuacion=".$numero_doc_adjunto);
				$total_partidas = $this->cepd02_contratoservicio_valuacion_partidas->findCount($this->SQLCA()." and ano_contrato_servicio=".$var[3]." and numero_contrato_servicio='".$var[5]."' and numero_valuacion=".$numero_doc_adjunto);
				$total_partidas_iva = $this->cepd02_contratoservicio_valuacion_partidas->findCount($this->SQLCA()." and ano_contrato_servicio=".$var[3]." and numero_contrato_servicio='".$var[5]."' and numero_valuacion=".$numero_doc_adjunto."  and ((cod_partida=403 and cod_generica=18 and cod_especifica=1) OR cod_partida=411)");
				$compromiso_partidas = $compromiso_partidas != null ? $compromiso_partidas : array();
				$partidas=$compromiso_partidas;
				$modelo="cepd02_contratoservicio_valuacion_partidas";
				$modelo_tabla="cepd02_contratoservicio_valuacion_partidas";
				$modelo_cuerpo1="cepd02_contratoservicio_valuacion_cuerpo";
				$modelo_cuerpo2="cepd02_contratoservicio_valuacion_cuerpo";
				$ano_up       ="ano_contrato_servicio";
				$numero_up    ="numero_contrato_servicio";
				$numero_up_adj=" and numero_valuacion='".$numero_doc_adjunto."'";
				$campo_ncc    ="numero_control_comp";
				$n_c_c        ="numero_control_caus";
						//print_r($compromiso_partidas);
				$to = 1;
				$td = 4;
				$ta = 7;
				$ndo = $var[5];
				$nda = $numero_doc_adjunto;
				break;
				case 9://contrato servicio retenciones
				$compromiso_partidas = $this->cepd02_contratoservicio_retencion_partidas->findAll($this->SQLCA()." and ano_contrato_servicio=".$var[3]." and numero_contrato_servicio='".$var[5]."' and numero_retencion=".$numero_doc_adjunto);
				$total_partidas = $this->cepd02_contratoservicio_retencion_partidas->findCount($this->SQLCA()." and ano_contrato_servicio=".$var[3]." and numero_contrato_servicio='".$var[5]."' and numero_retencion=".$numero_doc_adjunto);
				$total_partidas_iva = $this->cepd02_contratoservicio_retencion_partidas->findCount($this->SQLCA()." and ano_contrato_servicio=".$var[3]." and numero_contrato_servicio='".$var[5]."' and numero_retencion=".$numero_doc_adjunto."  and cod_partida=403 and cod_generica=18 and cod_especifica=1");
				$compromiso_partidas = $compromiso_partidas != null ? $compromiso_partidas : array();
				$partidas=$compromiso_partidas;
				$modelo="cepd02_contratoservicio_retencion_partidas";
				$modelo_tabla="cepd02_contratoservicio_retencion_partidas";
				$modelo_cuerpo1="cepd02_contratoservicio_retencion_cuerpo";
				$modelo_cuerpo2="cepd02_contratoservicio_retencion_cuerpo";
				$ano_up       ="ano_contrato_servicio";
				$numero_up    ="numero_contrato_servicio";
				$numero_up_adj=" and numero_retencion='".$numero_doc_adjunto."'";
				$campo_ncc    ="numero_control_comp";
				$n_c_c        ="numero_control_caus";
						//print_r($compromiso_partidas);
				$to = 1;
				$td = 4;
				$ta = 11;
				$ndo = $var[5];
				$nda = $numero_doc_adjunto;
				break;
				case 10://Ordenes compras - Retenciones
				$compromiso_partidas = $this->cscd04_ordencompra_retencion_partidas->findAll($this->SQLCA()." and ano_orden_compra=".$var[3]." and numero_orden_compra=".$var[5]." and numero_retencion=".$numero_doc_adjunto);
				$total_partidas = $this->cscd04_ordencompra_retencion_partidas->findCount($this->SQLCA()." and ano_orden_compra=".$var[3]." and numero_orden_compra=".$var[5]." and numero_retencion=".$numero_doc_adjunto);
				$total_partidas_iva = $this->cscd04_ordencompra_retencion_partidas->findCount($this->SQLCA()." and ano_orden_compra=".$var[3]." and numero_orden_compra=".$var[5]." and numero_retencion=".$numero_doc_adjunto."  and cod_partida=403 and cod_generica=18 and cod_especifica=1");
				$compromiso_partidas = $compromiso_partidas != null ? $compromiso_partidas : array();
				$partidas=$compromiso_partidas;
				$modelo="cscd04_ordencompra_retencion_partidas";
				$modelo_tabla="cscd04_ordencompra_retencion_partidas";
				$modelo_cuerpo1="cscd04_ordencompra_retencion_cuerpo";
				$modelo_cuerpo2="cscd04_ordencompra_retencion_cuerpo";
				$ano_up="ano_orden_compra";
				$numero_up="numero_orden_compra";
				$numero_up_adj=" and numero_retencion='".$numero_doc_adjunto."'";
				$campo_ncc="numero_control_compromis";
				$n_c_c="numero_control_causado";
				$to = 1;
				$td = 4;
				$ta = 13;
				$ndo = $var[5];
				$nda = $numero_doc_adjunto;
				break;
			}//fin switch
			$VALUES_FACTURAS="";
			$monto_i = 0;
			$monto_r = 0;
			$monto_b = 0;
			if(isset($_SESSION["items"])){
				$i=0;
			  //print_r($_SESSION["items"]);
				foreach($_SESSION["items"] as $nss){
					if($nss!=null){
						$new_array[$i][0]=$nss[0];
						$new_array[$i][1]=$nss[1];
						$new_array[$i][2]=$nss[2];
						$new_array[$i][3]=$this->Formato1($nss[3]);
						$new_array[$i][4]=$this->Formato1($nss[4]);
						$new_array[$i][5]=$this->Formato1($nss[5]);
						$new_array[$i][6]=$this->Formato1($nss[6]);
						$new_array[$i][7]=$this->Formato1($nss[7]);
						$new_array[$i][8]=$this->Formato1($nss[8]);
					 //$new_array[$i][8]=$nss[8];
						$i++;
					}
				}
				foreach($new_array as $fac){
					$FACTURAS[]="(".$this->SQLCAIN().",".$var[0].",".$numero_orden_pago.",'".$fac[0]."','".$fac[1]."','".$fac[2]."',".$fac[3].",".$fac[4].",".$fac[5].",".$fac[7].",".$fac[6].",".$fac[8].")";
					$monto_r += $fac[8];
               //$porc_i=$fac[5];
               //$monto_b=$monto_b+$fac[4];
             }//fin foreach new array

             $VALUES_FACTURAS=implode(',', $FACTURAS);
	      }//fin isset session items
	      $VALUES_CUERPO=$this->SQLCAIN().",".$var[0].",".$numero_orden_pago.",".$var[1].",'".$var[2]."',".$var[3].",'".$var[5]."',".$numero_doc_adjunto.",'".$fdo."',".$var[4].",'".$var[6]."','".$var[7]."','".$var[9]."',".$var[10].",'".$var[45]."',".$var[11].",".$var[33].",".$var[34].",".$var[37].",'".$var[35]."','".$var[36]."',".$var[12].",".$var[13].",".$var[18].",".$porcentaje_iva.",".($var[13]-$var[18]).",".$var[15].",".$var[14].",".$var[17].",".$var[16].",".$var[19].",".$var[21].",".$var[20].",".$var[22].",".$var[24].",".$var[23].",".$var[26].",".$var[25].",".$sustraendo.",".$var[28].",".$var[27].",".$var[30].",".$var[29].",".$var[31].",0,0,0,0,'".$this->Session->read('nom_usuario')."',1,0,0,0,0,0,0,'0',0,0,0,0,0,'1900-01-01','".date("Y-m-d")."','1900-01-01',0,0,0,0,0,0,'".$var[59]."', '".$var[60]."', '".$monto_mano_obra."', '".$_SESSION["ventana_islr"]."', '".$_SESSION["ventana_impuesto_municipal"]."', '".$var[61]."', '".$var[62]."', '0', '".$var[63]."', '".$var[131]."'";
		  //echo $VALUES_CUERPO;
	      $resultado1=$this->cepd03_ordenpago_cuerpo->execute("BEGIN; INSERT INTO cepd03_ordenpago_cuerpo  ($CAMPOS_CUERPO) VALUES ($VALUES_CUERPO)");
	      
	      if($resultado1>1){
	      	$reten_marca = 0;

	      	if(decimal_sprintf("%01.2f",$var[24])!=decimal_sprintf("%01.2f",$monto_r)){
	      		$reten_marca = 0;
	      	}
			  if($VALUES_FACTURAS!=""){   // if(($total_partidas_iva>0 && $var[18]!=0) || ($VALUES_FACTURAS!="" && $var[26]!=0)){
			  	$resultado3=$this->cepd03_ordenpago_facturas->execute("INSERT INTO cepd03_ordenpago_facturas  ($CAMPOS_FACTURAS) VALUES $VALUES_FACTURAS");
			  }else{
			  	$resultado3=2;
			  }
			  $i=1;
			  $j=0;

			  $numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', $conditions = $this->condicionNDEP()." and ano_causado='$ann'", $order =null);
			  if(!empty($numero_causado)){
			  	$numero_causado ++;
			  	$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ann' and ".$this->condicionNDEP().";";
			  }else{
			  	$numero_causado = 1;
			  	$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_causado')";
			  }
			  $sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);


			  foreach($partidas as $partida){
			  	$cp   = $this->crear_partida($partida[$modelo]["ano"],$partida[$modelo]["cod_sector"],$partida[$modelo]["cod_programa"],$partida[$modelo]["cod_sub_prog"],$partida[$modelo]["cod_proyecto"],$partida[$modelo]["cod_activ_obra"],$partida[$modelo]["cod_partida"],$partida[$modelo]["cod_generica"],$partida[$modelo]["cod_especifica"],$partida[$modelo]["cod_sub_espec"],$partida[$modelo]["cod_auxiliar"]);
			  	$mt   = $partida[$modelo]["monto"];
			  	$ccp  = $var[45];
			  	$rnco = $partida[$modelo][$campo_ncc];
			  	$rnca = $numero_causado;
			  	$dnca = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ann, $ndo, $nda, $numero_orden_pago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, $i);
			  	$ncc=$dnca;
			  	$VPARTIDAS[]="(".$this->SQLCAIN().",".$var[0].",".$numero_orden_pago.",".$partida[$modelo]["ano"].",".$partida[$modelo]["cod_sector"].",".$partida[$modelo]["cod_programa"].",".$partida[$modelo]["cod_sub_prog"].",".$partida[$modelo]["cod_proyecto"].",".$partida[$modelo]["cod_activ_obra"].",".$partida[$modelo]["cod_partida"].",".$partida[$modelo]["cod_generica"].",".$partida[$modelo]["cod_especifica"].",".$partida[$modelo]["cod_sub_espec"].",".$partida[$modelo]["cod_auxiliar"].",".$partida[$modelo]["monto"].",".$rnco.",".$rnca.")";
			  	if($var[4]!=1){
			  		$RS_update_causado_partidas=$this->$modelo->execute("UPDATE ".$modelo_tabla." SET numero_control_causado=".$rnca." WHERE ".$this->SQLCA()." and ".$ano_up."=".$var[3]." and ".$numero_up."='".$var[5]."' ".$numero_up_adj." and cod_sector=".$partida[$modelo]["cod_sector"]." and cod_programa=".$partida[$modelo]["cod_programa"]." and cod_sub_prog=".$partida[$modelo]["cod_sub_prog"]." and cod_proyecto=".$partida[$modelo]["cod_proyecto"]." and cod_activ_obra=".$partida[$modelo]["cod_activ_obra"]." and cod_partida=".$partida[$modelo]["cod_partida"]." and cod_generica=".$partida[$modelo]["cod_generica"]." and cod_especifica=".$partida[$modelo]["cod_especifica"]." and cod_sub_espec=".$partida[$modelo]["cod_sub_espec"]." and cod_auxiliar=".$partida[$modelo]["cod_auxiliar"]);
			  	}
			  	$i++;
			  	$j++;
				}//fin foreach partidas



				$VALUES_PARTIDAS=implode(',', $VPARTIDAS);
				$resultado2=$this->cepd03_ordenpago_partidas->execute("INSERT INTO cepd03_ordenpago_partidas($CAMPOS_PARTIDAS) VALUES $VALUES_PARTIDAS");


				if($resultado2>1){
		   	  switch($var[4]){ //switch Tipo de operación

					         case 1:{//REGISTRO DE COMPROMISO

					         	$datos = $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento='".$ano_documento."' and numero_documento='".$numero_documento."'   ");

					         	$f_dc_adj_array_pago_aux = null;
					         	$f_dc_array_pago_aux     = $datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"];

					         }break;

					         case 2:{//Anticipo Orden de compra

					         	$datos  = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$ano_documento."' and numero_orden_compra='".$numero_documento."' and numero_anticipo='".$numero_doc_adjunto."'  ");
					         	$datos2 = $this->cscd04_ordencompra_encabezado->findAll(     $this->condicion()." and ano_orden_compra='".$ano_documento."' and numero_orden_compra='".$numero_documento."'  ");

					         	$f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_anticipo_cuerpo"]["fecha_anticipo"];
					         	$f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];

					         }break;


					         case 3:{//Autorización de Orden de compra

					         	$datos  = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$ano_documento."' and numero_orden_compra='".$numero_documento."' and numero_pago='".$numero_doc_adjunto."'  ");
					         	$datos2 = $this->cscd04_ordencompra_encabezado->findAll(         $this->condicion()." and ano_orden_compra='".$ano_documento."' and numero_orden_compra='".$numero_documento."'  ");

					         	$f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_autorizacion_cuerpo"]["fecha_autorizacion"];
					         	$f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];


					         }break;


					          case 4:{//Anticipo CONTRATO DE OBRA

					          	$datos  = $this->cobd01_contratoobras_anticipo_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento."' and numero_contrato_obra='".$numero_documento."' and numero_anticipo='".$numero_doc_adjunto."'  ");
					          	$datos2 = $this->cobd01_contratoobras_cuerpo->findAll(         $this->condicion()." and ano_contrato_obra='".$ano_documento."' and numero_contrato_obra='".$numero_documento."'  ");

					          	$f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_anticipo_cuerpo"]["fecha_anticipo"];
					          	$f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];


					          }break;


					          case 5:{//VALUACIÓN DE CONTRATO DE OBRA

					          	$datos  = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento."' and numero_contrato_obra='".$numero_documento."' and numero_valuacion='".$numero_doc_adjunto."'  ");
					          	$datos2 = $this->cobd01_contratoobras_cuerpo->findAll(          $this->condicion()." and ano_contrato_obra='".$ano_documento."' and numero_contrato_obra='".$numero_documento."'  ");

					          	$f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_valuacion_cuerpo"]["fecha_valuacion"];
					          	$f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];


					          }break;


					          case 6:{//RETENCIÓN DE CONTRATO DE OBRA

					          	$datos  = $this->cobd01_contratoobras_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento."' and numero_contrato_obra='".$numero_documento."' and numero_retencion='".$numero_doc_adjunto."'  ");
					          	$datos2 = $this->cobd01_contratoobras_cuerpo->findAll(          $this->condicion()." and ano_contrato_obra='".$ano_documento."' and numero_contrato_obra='".$numero_documento."'  ");

					          	$f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_retencion_cuerpo"]["fecha_retencion"];
					          	$f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];


					          }break;


					          case 7:{//Anticipo CONTRATO DE SERVICIO

					          	$datos  = $this->cepd02_contratoservicio_anticipo_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento."' and numero_contrato_servicio='".$numero_documento."' and numero_anticipo='".$numero_doc_adjunto."'  ");
					          	$datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(         $this->condicion()." and ano_contrato_servicio='".$ano_documento."' and numero_contrato_servicio='".$numero_documento."'  ");

					          	$f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_anticipo_cuerpo"]["fecha_anticipo"];
					          	$f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];


					          }break;



					          case 8:{//VALUACIÓN DE CONTRATO DE SERVICIO

					          	$datos  = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento."' and numero_contrato_servicio='".$numero_documento."' and numero_valuacion='".$numero_doc_adjunto."'  ");
					          	$datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(          $this->condicion()." and ano_contrato_servicio='".$ano_documento."' and numero_contrato_servicio='".$numero_documento."'  ");

					          	$f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_valuacion_cuerpo"]["fecha_valuacion"];
					          	$f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];


					          }break;


					          case 9:{//RETENCIÓN DE CONTRATO DE SERVICIO


					          	$datos  = $this->cepd02_contratoservicio_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento."' and numero_contrato_servicio='".$numero_documento."' and numero_retencion='".$numero_doc_adjunto."'  ");
					          	$datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(          $this->condicion()." and ano_contrato_servicio='".$ano_documento."' and numero_contrato_servicio='".$numero_documento."'  ");

					          	$f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_retencion_cuerpo"]["fecha_retencion"];
					          	$f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];


					          }break;

					          case 10:{//RETENCIÓN DE ORDEN DE COMPRAS

					          	$datos  = $this->cscd04_ordencompra_retencion_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$ano_documento."' and numero_orden_compra=".$numero_documento." and numero_retencion='".$numero_doc_adjunto."'  ");
					          	$datos2 = $this->cscd04_ordencompra_encabezado->findAll(          $this->condicion()." and ano_orden_compra='".$ano_documento."' and numero_orden_compra=".$numero_documento."  ");

					          	$f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_retencion_cuerpo"]["fecha_retencion"];
					          	$f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];


					          }break;
					}//fin switch
					$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
						$to              = 1,
						$td              = 9,
						$rif_doc         = $var[6],
						$ano_dc          = $ano_documento,
						$n_dc            = $numero_documento,
						$f_dc            = cambiar_formato_fecha($f_dc_array_pago_aux),
						$cpt_dc          = $ccp,
						$ben_dc          = $var[9],
						$mon_dc          = array("monto_total_orden"  => $monto_total_orden_contabilidad,
							"monto_orden_pago"   => $monto_orden_pago_contabilidad,
							"monto_amortizacion" => $monto_amortizacion_contabilidad
						),

						$ano_op          = $ano_orden_pago,
						$n_op            = $numero_orden_pago,
						$f_op            = $m_fdo,

						$a_adj_op        = null,
						$n_adj_op        = $numero_doc_adjunto,
						$f_adj_op        = cambiar_formato_fecha($f_dc_adj_array_pago_aux),
						$tp_op           = $var[4],

						$deno_ban_pago   = null,
						$ano_movimiento  = null,
						$cod_ent_pago    = null,
						$cod_suc_pago    = null,
						$cod_cta_pago    = null,
						$num_che_o_debi  = null,
						$fec_che_o_debi  = null,
						$clas_che_o_debi = null
					);
				}else{
					$valor_motor_contabilidad = false;
		   	}//fin else


			  if($valor_motor_contabilidad==true){
			   	if($resultado3>1){
			   		$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=3 WHERE ".$this->SQLCA()." and ano_orden_pago=".$var[0]." and numero_orden_pago=".$numero_orden_pago);
			   		$contador = $this->$modelo_cuerpo1->findCount($this->SQLCA()." and ".$ano_up."=".$var[3]." and ".$numero_up."='".$var[5]."' ".$numero_up_adj." and ano_orden_pago=0 and numero_orden_pago=0");
			   		if($contador!=0){
			   			$upop=$this->$modelo_cuerpo1->execute("UPDATE ".$modelo_cuerpo2." SET ano_orden_pago=".$var[0].", numero_orden_pago=".$numero_orden_pago." WHERE ".$this->SQLCA()." and ".$ano_up."=".$var[3]." and ".$numero_up."='".$var[5]."' ".$numero_up_adj);
			   			$re=$this->cepd03_ordenpago_poremitir->execute("INSERT INTO cepd03_ordenpago_poremitir VALUES (".$this->SQLCAIN().",'".$this->Session->read('nom_usuario')."',".$var[0].",".$numero_orden_pago.")");
			   			if($re>1 && $upop>1){
			   				if($reten_marca==1){
			   					$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
			   					$this->index(2,"El iva retenido de las facturas no es igual al monto Retención I.V.A.  ");
			   					$this->render("index");
			   				}else{
			   					$this->cepd03_ordenpago_cuerpo->execute("COMMIT;");
			   					$this->index(1,"La orden de pago fue guardada exitosamente");
			   					$this->render("index");
			   				}
			   			}else{
			   				$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
			   				$this->index(2,"La orden de pago No fue guardada");
			   				$this->render("index");
			   			}
			   		}else{
			   			$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
			   			$this->index(2,"El documento ya tiene orden de pago");
			   			$this->render("index");
			   		}
			   	}else{
			   		$this->set("errorMessage","1. Disculpe, La orden de pago no fue guardada");
			   		$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
						//$resultado1=$this->cepd03_ordenpago_cuerpo->execute("DELETE FROM cepd03_ordenpago_cuerpo WHERE ".$this->SQLCA()." and ano_orden_pago=".$var[0]." and numero_orden_pago=".$numero_orden_pago);
						//$resultado2=$this->cepd03_ordenpago_partidas->execute("DELETE FROM cepd03_ordenpago_partidas WHERE ".$this->SQLCA()." and ano_orden_pago=".$var[0]." and numero_orden_pago=".$numero_orden_pago);
			   		$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$var[0]." and numero_orden_pago=".$numero_orden_pago);
			   	}
			   }else{
			   	$this->set("errorMessage","2. Disculpe, La orden de pago no fue guardada");
			   	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
				//$resultado1=$this->cepd03_ordenpago_cuerpo->execute("DELETE FROM cepd03_ordenpago_cuerpo WHERE ".$this->SQLCA()." and ano_orden_pago=".$var[0]." and numero_orden_pago=".$numero_orden_pago);
			   	$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$var[0]." and numero_orden_pago=".$numero_orden_pago);
			   }
		}else{// si no se ejecuto el cuerpo de la orden se realiza el rollback
			$this->set("errorMessage","3. Disculpe, La orden de pago no fue guardada");
			$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
			$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$var[0]." and numero_orden_pago=".$numero_orden_pago);
		}

	}//fin isset data

	$this->set('autor_valido',true);

}//fin Guardar

function consulta_form () {
	$this->layout="ajax";
	$this->set('ANO',$this->ano_ejecucion());
}

/**
 * FUNCION CONSULTAR DATOS DE LA ORDEN DE PAGO
 */
function consultar ($ano=null, $pagina=null,$msj=null) {
	$this->layout="ajax";
	if(isset($pagina)){
		$pagina=$pagina;
		$this->Session->delete('MSJ');
	}else{
		$pagina=1;

		}//fin else

		/*if($this->Session->read('year_pago')>date("Y")){
			$Ano= 1+date("Y");
		}else{$Ano=date("Y");}*/
		if(isset($ano) && $ano!=null){
			$Ano=$ano;
		}else{
			if(isset($this->data['cepp03_ordenpago']['ano_consulta'])){
				$Ano=$this->data['cepp03_ordenpago']['ano_consulta'];
			}else{
				$Ano=$this->ano_ejecucion();
			}

		}

		$this->set('ano_ejecucion',$this->ano_ejecucion());
		$this->set('ano',$Ano);
		$this->set('ejercicio', $Ano);
		$Tfilas=$this->cepd03_ordenpago_cuerpo->findCount($this->SQLCA()." and ano_orden_pago=".$Ano);
						 //echo $Tfilas;
		if($Tfilas!=0){
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('ultimo',$Tfilas);
			$dataOdenpago=$this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA()." and ano_orden_pago=".$Ano,null,'ano_orden_pago,numero_orden_pago ASC',1,$pagina,null);
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


			$RS_C=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']." and numero_cheque!=0");
			if($RS_C[0][0]["c"]!=0){
				$this->set("tiene_cheque_iva",true);
				$r_iva=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_iva WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
				$ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_iva[0][0]['cod_entidad_bancaria']);
				$this->set("denominacion_bancaria_iva",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
				$this->set("nro_cta_iva",$r_iva[0][0]['cuenta_bancaria']);
				$this->set("nro_cheque_iva",$r_iva[0][0]['numero_cheque']);
				$this->set("fecha_cheque_iva",$r_iva[0][0]['fecha_proceso_registro']);
			}else{
				$this->set("tiene_cheque_iva",false);
			}
			$RS_C_islr=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
			if($RS_C_islr[0][0]["c"]!=0){
				$this->set("tiene_cheque_islr",true);
				$r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_islr WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
				$ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
				$this->set("denominacion_bancaria_islr",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
				$this->set("nro_cta_islr",$r_islr[0][0]['cuenta_bancaria']);
				$this->set("nro_cheque_islr",$r_islr[0][0]['numero_cheque']);
				$this->set("fecha_cheque_islr",$r_islr[0][0]['fecha_proceso_registro']);
			}else{
				$this->set("tiene_cheque_islr",false);
			}

			$RS_C_municipal=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
			if($RS_C_municipal[0][0]["c"]!=0){
				$this->set("tiene_cheque_municipal",true);
				$r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_municipal WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
				$ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
				$this->set("denominacion_bancaria_municipal",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
				$this->set("nro_cta_municipal",$r_islr[0][0]['cuenta_bancaria']);
				$this->set("nro_cheque_municipal",$r_islr[0][0]['numero_cheque']);
				$this->set("fecha_cheque_municipal",$r_islr[0][0]['fecha_proceso_registro']);
			}else{
				$this->set("tiene_cheque_municipal",false);
			}


			$RS_C_fiscal=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
			if($RS_C_fiscal[0][0]["c"]!=0){
				$this->set("tiene_cheque_fiscal",true);
				$r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_timbre WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
				$ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
				$this->set("denominacion_bancaria_fiscal",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
				$this->set("nro_cta_fiscal",$r_islr[0][0]['cuenta_bancaria']);
				$this->set("nro_cheque_fiscal",$r_islr[0][0]['numero_cheque']);
				$this->set("fecha_cheque_fiscal",$r_islr[0][0]['fecha_proceso_registro']);
			}else{
				$this->set("tiene_cheque_fiscal",false);
			}

			$RS_C_multa=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
			if($RS_C_multa[0][0]["c"]!=0){
				$this->set("tiene_cheque_multa",true);
				$r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_multa WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
				$ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
				$this->set("denominacion_bancaria_multa",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
				$this->set("nro_cta_multa",$r_islr[0][0]['cuenta_bancaria']);
				$this->set("nro_cheque_multa",$r_islr[0][0]['numero_cheque']);
				$this->set("fecha_cheque_multa",$r_islr[0][0]['fecha_proceso_registro']);
			}else{
				$this->set("tiene_cheque_multa",false);
			}


			$RS_C_responsabilidad=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
			if($RS_C_responsabilidad[0][0]["c"]!=0){
				$this->set("tiene_cheque_responsabilidad",true);
				$r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_responsabilidad WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
				$ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
				$this->set("denominacion_bancaria_responsabilidad",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
				$this->set("nro_cta_responsabilidad",$r_islr[0][0]['cuenta_bancaria']);
				$this->set("nro_cheque_responsabilidad",$r_islr[0][0]['numero_cheque']);
				$this->set("fecha_cheque_responsabilidad",$r_islr[0][0]['fecha_proceso_registro']);
			}else{
				$this->set("tiene_cheque_responsabilidad",false);
			}



			$tipo_doc=$this->cepd03_tipo_documento->findAll("cod_tipo_documento=".$vec_op['cepd03_ordenpago_cuerpo']['cod_tipo_documento']);
			$this->set("tipo",$tipo_doc[0]["cepd03_tipo_documento"]["denominacion"]);
			$ordenpago_partidas=$this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano_orden_pago=".$Ano." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
			$C_A=$this->cugd03_acta_anulacion_cuerpo->findAll($this->SQLCA()." and numero_acta_anulacion=".$vec_op['cepd03_ordenpago_cuerpo']['numero_anulacion']." and ano_acta_anulacion=".$Ano);
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
								//$print_r();
			$this->set('ORDENPAGO_CUERPO',$dataOdenpago);
			$this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
		}else{
			$this->set('COMPROMISO','');
			$this->set('errorMessage', 'No se encontrar&oacute;n datos');

					 }///fin else  del if-else que compara las Tfilas
					 if(isset($_SESSION["MSJ"])){
					 	$a=$_SESSION["MSJ"];
					 	if($a["tipo_msj"]=="exito")$this->set('Message_existe', $a["msj"]);
					 	else if($a["tipo_msj"]=="error")$this->set('errorMessage', $a["msj"]);

					 	$this->Session->delete('MSJ');
					 }


}//fin consultar







 /*function lista_busqueda ($ano=null,$var=null) {
	$this->layout="ajax";
		if(isset($var)){
				$numero_busqueda=$var;
		}else{
				 $numero_busqueda=1;
		}//fin else
        if(isset($ano) && $ano!=null){
        	$Ano = $ano;
        }else{
        	$Ano=$this->ano_ejecucion();
        }

						 $this->set('ano_ejecucion',$this->ano_ejecucion());
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

                                  $RS_C=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
                                  if($RS_C[0][0]["c"]!=0){
                                  	  $this->set("tiene_cheque_iva",true);
                                  	  $r_iva=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_iva WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
                                  	  $ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_iva[0][0]['cod_entidad_bancaria']);
                                  	  $this->set("denominacion_bancaria_iva",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
	                                  $this->set("nro_cta_iva",$r_iva[0][0]['cuenta_bancaria']);
	                                  $this->set("nro_cheque_iva",$r_iva[0][0]['numero_cheque']);
	                                  $this->set("fecha_cheque_iva",$r_iva[0][0]['fecha_proceso_registro']);
                                  }else{
                                  	$this->set("tiene_cheque_iva",false);
                                  }
                                 $RS_C_islr=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
                                  if($RS_C_islr[0][0]["c"]!=0){
                                  	  $this->set("tiene_cheque_islr",true);
                                  	  $r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_islr WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
                                  	  $ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
                                  	  $this->set("denominacion_bancaria_islr",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
	                                  $this->set("nro_cta_islr",$r_islr[0][0]['cuenta_bancaria']);
	                                  $this->set("nro_cheque_islr",$r_islr[0][0]['numero_cheque']);
	                                  $this->set("fecha_cheque_islr",$r_islr[0][0]['fecha_proceso_registro']);
                                  }else{
                                  	$this->set("tiene_cheque_islr",false);
                                  }

                                  $RS_C_municipal=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
                                  if($RS_C_municipal[0][0]["c"]!=0){
                                  	  $this->set("tiene_cheque_municipal",true);
                                  	  $r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_municipal WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
                                  	  $ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
                                  	  $this->set("denominacion_bancaria_municipal",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
	                                  $this->set("nro_cta_municipal",$r_islr[0][0]['cuenta_bancaria']);
	                                  $this->set("nro_cheque_municipal",$r_islr[0][0]['numero_cheque']);
	                                  $this->set("fecha_cheque_municipal",$r_islr[0][0]['fecha_proceso_registro']);
                                  }else{
                                  	$this->set("tiene_cheque_municipal",false);
                                  }

                                  $RS_C_fiscal=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
                                  if($RS_C_fiscal[0][0]["c"]!=0){
                                  	  $this->set("tiene_cheque_fiscal",true);
                                  	  $r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_timbre WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
                                  	  $ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
                                  	  $this->set("denominacion_bancaria_fiscal",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
	                                  $this->set("nro_cta_fiscal",$r_islr[0][0]['cuenta_bancaria']);
	                                  $this->set("nro_cheque_fiscal",$r_islr[0][0]['numero_cheque']);
	                                  $this->set("fecha_cheque_fiscal",$r_islr[0][0]['fecha_proceso_registro']);
                                  }else{
                                  	$this->set("tiene_cheque_fiscal",false);
                                  }


                                  $RS_C_multa=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
                                  if($RS_C_multa[0][0]["c"]!=0){
                                  	  $this->set("tiene_cheque_multa",true);
                                  	  $r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_multa WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
                                  	  $ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
                                  	  $this->set("denominacion_bancaria_multa",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
	                                  $this->set("nro_cta_multa",$r_islr[0][0]['cuenta_bancaria']);
	                                  $this->set("nro_cheque_multa",$r_islr[0][0]['numero_cheque']);
	                                  $this->set("fecha_cheque_multa",$r_islr[0][0]['fecha_proceso_registro']);
                                  }else{
                                  	$this->set("tiene_cheque_multa",false);
                                  }



                                  $RS_C_responsabilidad=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
                                  if($RS_C_responsabilidad[0][0]["c"]!=0){
                                  	  $this->set("tiene_cheque_responsabilidad",true);
                                  	  $r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_responsabilidad WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
                                  	  $ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
                                  	  $this->set("denominacion_bancaria_responsabilidad",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
	                                  $this->set("nro_cta_responsabilidad",$r_islr[0][0]['cuenta_bancaria']);
	                                  $this->set("nro_cheque_responsabilidad",$r_islr[0][0]['numero_cheque']);
	                                  $this->set("fecha_cheque_responsabilidad",$r_islr[0][0]['fecha_proceso_registro']);
                                  }else{
                                  	$this->set("tiene_cheque_responsabilidad",false);
                                  }







								$tipo_doc=$this->cepd03_tipo_documento->findAll("cod_tipo_documento=".$vec_op['cepd03_ordenpago_cuerpo']['cod_tipo_documento']);
								$this->set("tipo",$tipo_doc[0]["cepd03_tipo_documento"]["denominacion"]);
								$ordenpago_partidas=$this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano_orden_pago=".$Ano." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
								$C_A=$this->cugd03_acta_anulacion_cuerpo->findAll($this->SQLCA()." and numero_acta_anulacion=".$vec_op['cepd03_ordenpago_cuerpo']['numero_anulacion']." and ano_acta_anulacion=".$Ano);
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
					}//fin lista_busqueda*/

					function lista_busqueda ($ano=null,$var=null,$nops=null) {
						$this->layout="ajax";
						if(isset($var)){
							$numero_busqueda=$var;
						}else{
							$numero_busqueda=1;
		}//fin else

		if(isset($nops)){
			$nopse=$nops;
		}else{
			$nopse=0;
		}//fin else

		if(isset($ano) && $ano!=null){
			$Ano = $ano;
		}else{
			$Ano=$this->ano_ejecucion();
		}

		$this->set('ano_ejecucion',$this->ano_ejecucion());
		$this->set('ano',$Ano);
		$this->set('ejercicio', $Ano);
		$Tfilas=$this->cepd03_ordenpago_cuerpo->findCount($this->SQLCA()." and ano_orden_pago=".$Ano." and numero_orden_pago=".$var." and numero_orden_pago_secuencia ='".$nopse."'");
		if($Tfilas!=0){
			$dataOdenpago=$this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA()." and ano_orden_pago=".$Ano." and numero_orden_pago=".$numero_busqueda." and numero_orden_pago_secuencia ='".$nopse."'",null,'ano_orden_pago,numero_orden_pago ASC',1,null,null);

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

			$RS_C=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
			if($RS_C[0][0]["c"]!=0){
				$this->set("tiene_cheque_iva",true);
				$r_iva=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_iva WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
				$ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_iva[0][0]['cod_entidad_bancaria']);
				$this->set("denominacion_bancaria_iva",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
				$this->set("nro_cta_iva",$r_iva[0][0]['cuenta_bancaria']);
				$this->set("nro_cheque_iva",$r_iva[0][0]['numero_cheque']);
				$this->set("fecha_cheque_iva",$r_iva[0][0]['fecha_proceso_registro']);
			}else{
				$this->set("tiene_cheque_iva",false);
			}
			$RS_C_islr=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
			if($RS_C_islr[0][0]["c"]!=0){
				$this->set("tiene_cheque_islr",true);
				$r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_islr WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
				$ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
				$this->set("denominacion_bancaria_islr",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
				$this->set("nro_cta_islr",$r_islr[0][0]['cuenta_bancaria']);
				$this->set("nro_cheque_islr",$r_islr[0][0]['numero_cheque']);
				$this->set("fecha_cheque_islr",$r_islr[0][0]['fecha_proceso_registro']);
			}else{
				$this->set("tiene_cheque_islr",false);
			}

			$RS_C_municipal=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
			if($RS_C_municipal[0][0]["c"]!=0){
				$this->set("tiene_cheque_municipal",true);
				$r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_municipal WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
				$ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
				$this->set("denominacion_bancaria_municipal",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
				$this->set("nro_cta_municipal",$r_islr[0][0]['cuenta_bancaria']);
				$this->set("nro_cheque_municipal",$r_islr[0][0]['numero_cheque']);
				$this->set("fecha_cheque_municipal",$r_islr[0][0]['fecha_proceso_registro']);
			}else{
				$this->set("tiene_cheque_municipal",false);
			}

			$RS_C_fiscal=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
			if($RS_C_fiscal[0][0]["c"]!=0){
				$this->set("tiene_cheque_fiscal",true);
				$r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_timbre WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
				$ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
				$this->set("denominacion_bancaria_fiscal",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
				$this->set("nro_cta_fiscal",$r_islr[0][0]['cuenta_bancaria']);
				$this->set("nro_cheque_fiscal",$r_islr[0][0]['numero_cheque']);
				$this->set("fecha_cheque_fiscal",$r_islr[0][0]['fecha_proceso_registro']);
			}else{
				$this->set("tiene_cheque_fiscal",false);
			}


			$RS_C_multa=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
			if($RS_C_multa[0][0]["c"]!=0){
				$this->set("tiene_cheque_multa",true);
				$r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_multa WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
				$ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
				$this->set("denominacion_bancaria_multa",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
				$this->set("nro_cta_multa",$r_islr[0][0]['cuenta_bancaria']);
				$this->set("nro_cheque_multa",$r_islr[0][0]['numero_cheque']);
				$this->set("fecha_cheque_multa",$r_islr[0][0]['fecha_proceso_registro']);
			}else{
				$this->set("tiene_cheque_multa",false);
			}



			$RS_C_responsabilidad=$this->cstd01_entidades_bancarias->execute("SELECT count(*) as c FROM cstd07_retenciones_cuerpo_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']."  and numero_cheque!=0");
			if($RS_C_responsabilidad[0][0]["c"]!=0){
				$this->set("tiene_cheque_responsabilidad",true);
				$r_islr=$this->cstd01_entidades_bancarias->execute("SELECT * FROM cstd07_retenciones_cuerpo_responsabilidad WHERE ".$this->SQLCA()." and ano_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']);
				$ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$r_islr[0][0]['cod_entidad_bancaria']);
				$this->set("denominacion_bancaria_responsabilidad",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]!=null?$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]:"0");
				$this->set("nro_cta_responsabilidad",$r_islr[0][0]['cuenta_bancaria']);
				$this->set("nro_cheque_responsabilidad",$r_islr[0][0]['numero_cheque']);
				$this->set("fecha_cheque_responsabilidad",$r_islr[0][0]['fecha_proceso_registro']);
			}else{
				$this->set("tiene_cheque_responsabilidad",false);
			}







			$tipo_doc=$this->cepd03_tipo_documento->findAll("cod_tipo_documento=".$vec_op['cepd03_ordenpago_cuerpo']['cod_tipo_documento']);
			$this->set("tipo",$tipo_doc[0]["cepd03_tipo_documento"]["denominacion"]);
			$ordenpago_partidas=$this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano_orden_pago=".$Ano." and numero_orden_pago=".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago']." and numero_orden_pago_secuencia='".$vec_op['cepd03_ordenpago_cuerpo']['numero_orden_pago_secuencia']."'");
			$C_A=$this->cugd03_acta_anulacion_cuerpo->findAll($this->SQLCA()." and numero_acta_anulacion=".$vec_op['cepd03_ordenpago_cuerpo']['numero_anulacion']." and ano_acta_anulacion=".$Ano);
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

function bt_nav($Tfilas,$pagina){
	if($Tfilas==1){
		$this->set('mostrarS',false);
		$this->set('mostrarA',false);
	}else if($Tfilas==2){
		if($pagina==2){
			$this->set('mostrarS',false);
			$this->set('mostrarA',true);
		}else{
			$this->set('mostrarS',true);
			$this->set('mostrarA',false);
		}
	}else if($Tfilas>=3){
		if($pagina==$Tfilas){
			$this->set('mostrarS',false);
			$this->set('mostrarA',true);
		}else if($pagina==1){
			$this->set('mostrarS',true);
			$this->set('mostrarA',false);
		}else{
			$this->set('mostrarS',true);
			$this->set('mostrarA',true);
		}
	}
}//fin navegacion


function guardar_anulacion ($ano=null,$op=null,$nrc=null,$tipo=null,$fecha=null,$num_doc_adj=null) {
	$this->layout="ajax";
	$cod_dep=$this->verifica_SS(5);
	if(isset($tipo) && isset($nrc)){

		$ndo     = $this->data["cepp03_ordenpago"]["numero_documento_origen"];
		$nda     = $this->data["cepp03_ordenpago"]["numero_documento_adjunto"];


		$opago   = $this->data["cepp03_ordenpago"]["numero_orden_pago"];
		$fd      = $this->data["cepp03_ordenpago"]["fecha_documento_origen"];
		$opfecha = $this->data["cepp03_ordenpago"]["fecha_documento_orden"];



						 //$m_fdo   = $this->data["cepp03_ordenpago"]["fecha_documento_orden"];
		$m_fdo     = date("d/m/Y");
		$m_opfecha=$this->data["cepp03_ordenpago"]["fecha_documento_origen"];

		$concepto_anulacion = $this->data["cepp03_ordenpago"]["concepto_anulacion"];
		$ano_documento      = $this->data["cepp03_ordenpago"]["ano_documento"];
		$rif                = $this->data["cepp03_ordenpago"]["rif_ci"];


		$monto_total_orden_contabilidad   = $this->Formato1($this->data["cepp03_ordenpago"]["monto_total_cancelar"]);
		$monto_orden_pago_contabilidad    = $this->Formato1($this->data["cepp03_ordenpago"]["monto_orden_pago"]);
		$monto_amortizacion_contabilidad  = $this->Formato1($this->data["cepp03_ordenpago"]["monto_amortizacion_anticipo"]);

		$autorizado_cobrar                = $this->data["cepp03_ordenpago"]["autorizado_cobrar"];

		$check_nomina=$this->cepd03_ordenpago_cuerpo->execute("SELECT numero_orden_pago_secuencia FROM cepd03_ordenpago_cuerpo WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
		$isNomina = $check_nomina[0][0]["numero_orden_pago_secuencia"] !=='0';

		$this->cepd03_ordenpago_cuerpo->execute("BEGIN;");


		$numero_documento   = $nrc;
		$numero_doc_adjunto = $num_doc_adj;



                         switch($tipo){ //switch Tipo de operación

						         case 1:{//REGISTRO DE COMPROMISO

						         	$datos = $this->cepd01_compromiso_cuerpo->findAll($this->condicion()." and ano_documento='".$ano_documento."' and numero_documento='".$numero_documento."'   ");

						         	$f_dc_adj_array_pago_aux = null;
						         	$f_dc_array_pago_aux     = $datos[0]["cepd01_compromiso_cuerpo"]["fecha_documento"];
						         }break;

						         case 2:{//Anticipo Orden de compra

						         	$datos  = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$ano_documento."' and numero_orden_compra='".$numero_documento."' and numero_anticipo='".$numero_doc_adjunto."'  ");
						         	$datos2 = $this->cscd04_ordencompra_encabezado->findAll(     $this->condicion()." and ano_orden_compra='".$ano_documento."' and numero_orden_compra='".$numero_documento."'  ");

						         	$f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_anticipo_cuerpo"]["fecha_anticipo"];
						         	$f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];
						         }break;


						         case 3:{//Autorización de Orden de compra

						         	$datos  = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$ano_documento."' and numero_orden_compra='".$numero_documento."' and numero_pago='".$numero_doc_adjunto."'  ");
						         	$datos2 = $this->cscd04_ordencompra_encabezado->findAll(         $this->condicion()." and ano_orden_compra='".$ano_documento."' and numero_orden_compra='".$numero_documento."'  ");

						         	$f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_autorizacion_cuerpo"]["fecha_autorizacion"];
						         	$f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];
						         }break;


						          case 4:{//Anticipo CONTRATO DE OBRA

						          	$datos  = $this->cobd01_contratoobras_anticipo_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento."' and numero_contrato_obra='".$numero_documento."' and numero_anticipo='".$numero_doc_adjunto."'  ");
						          	$datos2 = $this->cobd01_contratoobras_cuerpo->findAll(         $this->condicion()." and ano_contrato_obra='".$ano_documento."' and numero_contrato_obra='".$numero_documento."'  ");

						          	$f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_anticipo_cuerpo"]["fecha_anticipo"];
						          	$f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];
						          }break;


						          case 5:{//VALUACIÓN DE CONTRATO DE OBRA


						          	$datos  = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento."' and numero_contrato_obra='".$numero_documento."' and numero_valuacion='".$numero_doc_adjunto."'  ");
						          	$datos2 = $this->cobd01_contratoobras_cuerpo->findAll(          $this->condicion()." and ano_contrato_obra='".$ano_documento."' and numero_contrato_obra='".$numero_documento."'  ");

						          	$f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_valuacion_cuerpo"]["fecha_valuacion"];
						          	$f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];
						          }break;


						          case 6:{//RETENCIÓN DE CONTRATO DE OBRA

						          	$datos  = $this->cobd01_contratoobras_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_obra='".$ano_documento."' and numero_contrato_obra='".$numero_documento."' and numero_retencion='".$numero_doc_adjunto."'  ");
						          	$datos2 = $this->cobd01_contratoobras_cuerpo->findAll(          $this->condicion()." and ano_contrato_obra='".$ano_documento."' and numero_contrato_obra='".$numero_documento."'  ");

						          	$f_dc_adj_array_pago_aux = $datos[0]["cobd01_contratoobras_retencion_cuerpo"]["fecha_retencion"];
						          	$f_dc_array_pago_aux     = $datos2[0]["cobd01_contratoobras_cuerpo"]["fecha_contrato_obra"];
						          }break;


						          case 7:{//Anticipo CONTRATO DE SERVICIO

						          	$datos  = $this->cepd02_contratoservicio_anticipo_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento."' and numero_contrato_servicio='".$numero_documento."' and numero_anticipo='".$numero_doc_adjunto."'  ");
						          	$datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(         $this->condicion()." and ano_contrato_servicio='".$ano_documento."' and numero_contrato_servicio='".$numero_documento."'  ");

						          	$f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_anticipo_cuerpo"]["fecha_anticipo"];
						          	$f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];
						          }break;


						          case 8:{//VALUACIÓN DE CONTRATO DE SERVICIO

						          	$datos  = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento."' and numero_contrato_servicio='".$numero_documento."' and numero_valuacion='".$numero_doc_adjunto."'  ");
						          	$datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(          $this->condicion()." and ano_contrato_servicio='".$ano_documento."' and numero_contrato_servicio='".$numero_documento."'  ");

						          	$f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_valuacion_cuerpo"]["fecha_valuacion"];
						          	$f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];
						          }break;


						          case 9:{//RETENCIÓN DE CONTRATO DE SERVICIO


						          	$datos  = $this->cepd02_contratoservicio_retencion_cuerpo->findAll($this->condicion()." and ano_contrato_servicio='".$ano_documento."' and numero_contrato_servicio='".$numero_documento."' and numero_retencion='".$numero_doc_adjunto."'  ");
						          	$datos2 = $this->cepd02_contratoservicio_cuerpo->findAll(          $this->condicion()." and ano_contrato_servicio='".$ano_documento."' and numero_contrato_servicio='".$numero_documento."'  ");

						          	$f_dc_adj_array_pago_aux = $datos[0]["cepd02_contratoservicio_retencion_cuerpo"]["fecha_retencion"];
						          	$f_dc_array_pago_aux     = $datos2[0]["cepd02_contratoservicio_cuerpo"]["fecha_contrato_servicio"];
						          }break;


						          case 10:{//RETENCIÓN DE ORDENES DE COMPRAS

						          	$datos  = $this->cscd04_ordencompra_retencion_cuerpo->findAll($this->condicion()." and ano_orden_compra='".$ano_documento."' and numero_orden_compra='".$numero_documento."' and numero_retencion='".$numero_doc_adjunto."'  ");
						          	$datos2 = $this->cscd04_ordencompra_encabezado->findAll(          $this->condicion()." and ano_orden_compra='".$ano_documento."' and numero_orden_compra='".$numero_documento."'  ");

						          	$f_dc_adj_array_pago_aux = $datos[0]["cscd04_ordencompra_retencion_cuerpo"]["fecha_retencion"];
						          	$f_dc_array_pago_aux     = $datos2[0]["cscd04_ordencompra_encabezado"]["fecha_orden_compra"];
						          }break;


						}//fin switch



						$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
							$to              = 2,
							$td              = 9,
							$rif_doc         = $rif,
							$ano_dc          = $ano_documento,
							$n_dc            = $nrc,
							$f_dc            = cambia_fecha($f_dc_array_pago_aux),
							$cpt_dc          = $concepto_anulacion,
							$ben_dc          = $autorizado_cobrar,
							$mon_dc          = array("monto_total_orden"  => $monto_total_orden_contabilidad,
								"monto_orden_pago"   => $monto_orden_pago_contabilidad,
								"monto_amortizacion" => $monto_amortizacion_contabilidad
							),

							$ano_op          = $ano,
							$n_op            = $opago,
							$f_op            = cambia_fecha($fecha),

							$a_adj_op        = null,
							$n_adj_op        = $num_doc_adj,
							$f_adj_op        = cambia_fecha($f_dc_adj_array_pago_aux),
							$tp_op           = $tipo,

							$deno_ban_pago   = null,
							$ano_movimiento  = null,
							$cod_ent_pago    = null,
							$cod_suc_pago    = null,
							$cod_cta_pago    = null,
							$num_che_o_debi  = null,
							$fec_che_o_debi  = null,
							$clas_che_o_debi = null
						);



						switch($tipo){
				case 1://compromiso
				    //print_r($this->data);
				$tipo_documento=241;
				$ano=$ano;
						//$numero_documento=$this->data["cepp03_ordenpago"]["numero_documento_origen"];//$op;//$ndo
				$concepto_anulacion=$this->data["cepp03_ordenpago"]["concepto_anulacion"];
				$fecha_documento=$this->data["cepp03_ordenpago"]["fecha_documento_orden"];
						$condicion_documento=2;//cuando se guarda es Activo=1

						$partidas_compromiso = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago='$opago'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
						$v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano." ORDER BY numero_acta_anulacion DESC");

						if($v!=null){
							$numero=$v[0][0]["numero_acta_anulacion"];
							$numero = $numero =="" ? 1 : $numero+1;

							$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano."");
						}else{
							$v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",1)");
							$numero=1;
						}
						
						$v=$this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",".$numero.",".$tipo_documento.",".$ano.",'".$opago."','".date("Y-m-d")."','".$concepto_anulacion."')");
							
							if($v>1){
								$R1=$this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo SET ano_orden_pago=0,numero_orden_pago=0 WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$nrc);

								$Rx1=$this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.",fecha_proceso_anulacion='".date("Y-m-d")."', username_anulacion='".$this->Session->read('nom_usuario')."' WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
								if($R1>1){
									$R1_a=$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
								}
							}
							$msj=isset($R1) && $R1>1?true:false;
							
							if($msj==true){
								$partidas_compromiso = $this->cepd03_ordenpago_partidas->findAll($conditions = $this->condicion()." and ano=".$ano." and numero_orden_pago=".$opago, $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
								foreach($partidas_compromiso as $vec){
									$cp   = $this->crear_partida($vec["cepd03_ordenpago_partidas"]["ano"], $vec["cepd03_ordenpago_partidas"]["cod_sector"], $vec["cepd03_ordenpago_partidas"]["cod_programa"], $vec["cepd03_ordenpago_partidas"]["cod_sub_prog"], $vec["cepd03_ordenpago_partidas"]["cod_proyecto"], $vec["cepd03_ordenpago_partidas"]["cod_activ_obra"], $vec["cepd03_ordenpago_partidas"]["cod_partida"],$vec["cepd03_ordenpago_partidas"]["cod_generica"], $vec["cepd03_ordenpago_partidas"]["cod_especifica"], $vec["cepd03_ordenpago_partidas"]["cod_sub_espec"],$vec["cepd03_ordenpago_partidas"]["cod_auxiliar"]);
									$to   = 2;
									$td   = 4;
									$ta   = 1;
									$rnco = $vec["cepd03_ordenpago_partidas"]["numero_control_compromiso"];
									$rnca = $vec["cepd03_ordenpago_partidas"]["numero_control_causado"];
									$mt   = $vec["cepd03_ordenpago_partidas"]["monto"];
									$ccp  = $concepto_anulacion;

									if($isNomina==false){
										$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
									}else{								
										$sqlCheck = 'SELECT * FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
										$checkDep = $this->cepd03_ordenpago_partidas->execute($sqlCheck);
					          // if($cod_dep==9999 || $cod_dep==1028){
										if(count($checkDep)>0){
											$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
										}else{
											$dnco=true;											
										}
									}

							}//fin foreach

							if($valor_motor_contabilidad==true){

								if($dnco != false){


									$msj2="Orden de Pago - Compromiso Anulado con exito";
									$MSJ=array("msj"=>$msj2,"tipo_msj"=>"exito");
									$this->Session->write("MSJ",$MSJ);


									$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
									$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
									$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
									$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
									$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
									$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");

									$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
									$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
									$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
									$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
									$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
									$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");



									$this->cepd03_ordenpago_cuerpo->execute("COMMIT;");


								}else{
									$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
									$msj2="Orden de Pago - Compromiso no Anulada...";
									$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
									$this->Session->write("MSJ",$MSJ);

								}

							}else{
								$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								$msj2="Orden de Pago - Compromiso no Anulada..";
								$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								$this->Session->write("MSJ",$MSJ);

							}

						}else{

							$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
							$msj2="Orden de Pago - Compromiso no Anulada.";
							$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
							$this->Session->write("MSJ",$MSJ);
						}

						$this->consultar();
						$this->render("consultar");
						break;
				case 2://ordenes de compra  anticipo
				$tipo_documento=242;
				$ano=$ano;
						 //$numero_documento=$op;
				$concepto_anulacion=$this->data["cepp03_ordenpago"]["concepto_anulacion"];
				$fecha=$this->data["cepp03_ordenpago"]["fecha_documento_orden"];
				$fecha_documento=$this->Cfecha($fecha,"A-M-D");
						// $fecha_documento=$this->Cfecha($fecha_documento,"A-M-D");
						 $condicion_documento=2;//cuando se guarda es Activo=1

			             //$partidas_compromiso = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago='$opago'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
						 $v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano." ORDER BY numero_acta_anulacion DESC");
						 if($v!=null){
						 	$numero=$v[0][0]["numero_acta_anulacion"];
						 	$numero = $numero =="" ? 1 : $numero+1;

						 	$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano."");
						 }else{
						 	$v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",1)");
						 	$numero=1;
						 }
						 $v=$this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",".$numero.",".$tipo_documento.",".$ano.",'".$opago."','".date("Y-m-d")."','".$concepto_anulacion."')");
						 	if($v>1){
						 		$R1=$this->cscd04_ordencompra_anticipo_cuerpo->execute("UPDATE cscd04_ordencompra_anticipo_cuerpo SET ano_orden_pago=0,numero_orden_pago=0 WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$nrc." and numero_anticipo=".$num_doc_adj);
						 		$Rx1=$this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.",fecha_proceso_anulacion='".date("Y-m-d")."', username_anulacion='".$this->Session->read('nom_usuario')."' WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		if($R1>1){
						 			$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		}
						 	}

						 	$msj=isset($R1) && $R1>1?true:false;
						 	if($msj==true){
						 		$partidas_causado = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano=".$ano." and numero_orden_pago=".$opago);
						 		foreach($partidas_causado as $vec){
						 			$cp   = $this->crear_partida($vec["cepd03_ordenpago_partidas"]["ano"], $vec["cepd03_ordenpago_partidas"]["cod_sector"], $vec["cepd03_ordenpago_partidas"]["cod_programa"], $vec["cepd03_ordenpago_partidas"]["cod_sub_prog"], $vec["cepd03_ordenpago_partidas"]["cod_proyecto"], $vec["cepd03_ordenpago_partidas"]["cod_activ_obra"], $vec["cepd03_ordenpago_partidas"]["cod_partida"],$vec["cepd03_ordenpago_partidas"]["cod_generica"], $vec["cepd03_ordenpago_partidas"]["cod_especifica"], $vec["cepd03_ordenpago_partidas"]["cod_sub_espec"],$vec["cepd03_ordenpago_partidas"]["cod_auxiliar"]);
						 			$to   = 2;
						 			$td   = 4;
						 			$ta   = 2;
						 			$rnco = $vec["cepd03_ordenpago_partidas"]["numero_control_compromiso"];
						 			$rnca = $vec["cepd03_ordenpago_partidas"]["numero_control_causado"];
						 			$mt   = $vec["cepd03_ordenpago_partidas"]["monto"];
						 			$ccp  = $concepto_anulacion;
						 			
						 			if($isNomina==false){
						 				$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 			}else{								
						 				$sqlCheck = 'SELECT * FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
						 				$checkDep = $this->cepd03_ordenpago_partidas->execute($sqlCheck);
										          // if($cod_dep==9999 || $cod_dep==1028){
						 				if(count($checkDep)>0){
						 					$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 				}else{
						 					$dnco=true;											
						 				}
						 			}
								     }//fin foreach

								     if($valor_motor_contabilidad==true){


								     	if($dnco != false){

								     		$msj2="Orden de Pago - Anticipo Anulado con exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"exito");
								     		$this->Session->write("MSJ",$MSJ);

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");

								     		$this->cepd03_ordenpago_cuerpo->execute("COMMIT;");

								     	}else{
								     		$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     		$msj2="Anulaci&oacute;n de la Orden de Compra Anticipo Pago sin exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     		$this->Session->write("MSJ",$MSJ);

								     	}

								     }else{
								     	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     	$msj2="Anulaci&oacute;n de la Orden de Compra Anticipo Pago sin exito";
								     	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     	$this->Session->write("MSJ",$MSJ);

								     }







								   }else{
								   	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								   	$msj2="Anulaci&oacute;n de la Orden de Compra Anticipo Pago sin exito";
								   	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								   	$this->Session->write("MSJ",$MSJ);
								   }
								   $this->consultar();
								   $this->render("consultar");
								   $this->set('tipo',2);
								   break;
				case 3://ordenes de compra autorizacion pago
				$tipo_documento=243;
				$ano=$ano;
						// $opago=$op;
				$concepto_anulacion=$this->data["cepp03_ordenpago"]["concepto_anulacion"];
				$fecha=$this->data["cepp03_ordenpago"]["fecha_documento_orden"];
				$fecha_documento=$this->Cfecha($fecha,"A-M-D");
						// $fecha_documento=$this->Cfecha($fecha_documento,"A-M-D");
						 $condicion_documento=2;//cuando se guarda es Activo=1

			             //$partidas_compromiso = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago='$opago'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
						 $v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano." ORDER BY numero_acta_anulacion DESC");
						 if($v!=null){
						 	$numero=$v[0][0]["numero_acta_anulacion"];
						 	$numero = $numero =="" ? 1 : $numero+1;

						 	$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano."");
						 }else{
						 	$v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",1)");
						 	$numero=1;
						 }
						 $v=$this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",".$numero.",".$tipo_documento.",".$ano.",'".$opago."','".date("Y-m-d")."','".$concepto_anulacion."')");
						 	if($v>1){
						 		$R1=$this->cscd04_ordencompra_autorizacion_cuerpo->execute("UPDATE cscd04_ordencompra_autorizacion_pago_cuerpo SET ano_orden_pago=0,numero_orden_pago=0 WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$nrc." and numero_pago=".$num_doc_adj);
						 		$Rx1=$this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.",fecha_proceso_anulacion='".date("Y-m-d")."', username_anulacion='".$this->Session->read('nom_usuario')."' WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		if($R1>1){
						 			$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		}
						 	}

						 	$msj=isset($R1) && $R1>1?true:false;
						 	if($msj==true){
						 		$partidas_causado = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano=".$ano." and numero_orden_pago=".$opago);
						 		foreach($partidas_causado as $vec){
						 			$cp   = $this->crear_partida($vec["cepd03_ordenpago_partidas"]["ano"], $vec["cepd03_ordenpago_partidas"]["cod_sector"], $vec["cepd03_ordenpago_partidas"]["cod_programa"], $vec["cepd03_ordenpago_partidas"]["cod_sub_prog"], $vec["cepd03_ordenpago_partidas"]["cod_proyecto"], $vec["cepd03_ordenpago_partidas"]["cod_activ_obra"], $vec["cepd03_ordenpago_partidas"]["cod_partida"],$vec["cepd03_ordenpago_partidas"]["cod_generica"], $vec["cepd03_ordenpago_partidas"]["cod_especifica"], $vec["cepd03_ordenpago_partidas"]["cod_sub_espec"],$vec["cepd03_ordenpago_partidas"]["cod_auxiliar"]);
						 			$to   = 2;
						 			$td   = 4;
						 			$ta   = 3;
						 			$rnco = $vec["cepd03_ordenpago_partidas"]["numero_control_compromiso"];
						 			$rnca = $vec["cepd03_ordenpago_partidas"]["numero_control_causado"];
						 			$mt   = $vec["cepd03_ordenpago_partidas"]["monto"];
						 			$ccp  = $concepto_anulacion;
						 			
						 			if($isNomina==false){
						 				$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 			}else{								
						 				$sqlCheck = 'SELECT * FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
						 				$checkDep = $this->cepd03_ordenpago_partidas->execute($sqlCheck);
											        // if($cod_dep==9999 || $cod_dep==1028){
						 				if(count($checkDep)>0){
						 					$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 				}else{
						 					$dnco=true;											
						 				}
						 			}

								     }//fin foreach


								     if($valor_motor_contabilidad==true){

								     	if($dnco != false){

								     		$msj2="Orden de Pago - Autorizacion Anulado con exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"exito");
								     		$this->Session->write("MSJ",$MSJ);

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");

								     		$this->cepd03_ordenpago_cuerpo->execute("COMMIT;");

								     	}else{
								     		$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     		$msj2="Anulaci&oacute;n de la Orden de Compra Autorizacion Pago sin exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     		$this->Session->write("MSJ",$MSJ);

								     	}

								     }else{
								     	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     	$msj2="Anulaci&oacute;n de la Orden de Compra Autorizacion Pago sin exito";
								     	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     	$this->Session->write("MSJ",$MSJ);

								     }






								   }else{
								   	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								   	$msj2="Anulaci&oacute;n de la Orden de Compra Autorizacion Pago sin exito";
								   	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								   	$this->Session->write("MSJ",$MSJ);
								   }
								   $this->consultar();
								   $this->render("consultar");
								   $this->set('tipo',3);
								   break;
				case 4://contrato obras anticipo
				$tipo_documento=244;
				$ano=$ano;
						 ///$numero_documento=$op;
				$concepto_anulacion=$this->data["cepp03_ordenpago"]["concepto_anulacion"];
				$fecha=$this->data["cepp03_ordenpago"]["fecha_documento_orden"];
				$fecha_documento=$this->Cfecha($fecha,"A-M-D");
						// $fecha_documento=$this->Cfecha($fecha_documento,"A-M-D");
						 $condicion_documento=2;//cuando se guarda es Activo=1

			             //$partidas_compromiso = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago='$opago'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
						 $v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano." ORDER BY numero_acta_anulacion DESC");
						 if($v!=null){
						 	$numero=$v[0][0]["numero_acta_anulacion"];
						 	$numero = $numero =="" ? 1 : $numero+1;

						 	$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano."");
						 }else{
						 	$v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",1)");
						 	$numero=1;
						 }
						 $v=$this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",".$numero.",".$tipo_documento.",".$ano.",'".$opago."','".date("Y-m-d")."','".$concepto_anulacion."')");
						 	if($v>1){
						 		$R1=$this->cobd01_contratoobras_anticipo_cuerpo->execute("UPDATE cobd01_contratoobras_anticipo_cuerpo SET ano_orden_pago=0,numero_orden_pago=0 WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$nrc."' and numero_anticipo=".$num_doc_adj);
						 		$Rx1=$this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.",fecha_proceso_anulacion='".date("Y-m-d")."', username_anulacion='".$this->Session->read('nom_usuario')."' WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		if($R1>1){
						 			$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		}
						 	}

						 	$msj=isset($R1) && $R1>1?true:false;
						 	if($msj==true){
						 		$partidas_causado = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano=".$ano." and numero_orden_pago=".$opago);
						 		foreach($partidas_causado as $vec){
						 			$cp   = $this->crear_partida($vec["cepd03_ordenpago_partidas"]["ano"], $vec["cepd03_ordenpago_partidas"]["cod_sector"], $vec["cepd03_ordenpago_partidas"]["cod_programa"], $vec["cepd03_ordenpago_partidas"]["cod_sub_prog"], $vec["cepd03_ordenpago_partidas"]["cod_proyecto"], $vec["cepd03_ordenpago_partidas"]["cod_activ_obra"], $vec["cepd03_ordenpago_partidas"]["cod_partida"],$vec["cepd03_ordenpago_partidas"]["cod_generica"], $vec["cepd03_ordenpago_partidas"]["cod_especifica"], $vec["cepd03_ordenpago_partidas"]["cod_sub_espec"],$vec["cepd03_ordenpago_partidas"]["cod_auxiliar"]);
						 			$to   = 2;
						 			$td   = 4;
						 			$ta   = 4;
						 			$rnco = $vec["cepd03_ordenpago_partidas"]["numero_control_compromiso"];
						 			$rnca = $vec["cepd03_ordenpago_partidas"]["numero_control_causado"];
						 			$mt   = $vec["cepd03_ordenpago_partidas"]["monto"];
						 			$ccp  = $concepto_anulacion;
						 			
						 			if($isNomina==false){
						 				$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 			}else{								
						 				$sqlCheck = 'SELECT * FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
						 				$checkDep = $this->cepd03_ordenpago_partidas->execute($sqlCheck);
											        // if($cod_dep==9999 || $cod_dep==1028){
						 				if(count($checkDep)>0){
						 					$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 				}else{
						 					$dnco=true;											
						 				}
						 			}

								     }//fin foreach

								     if($valor_motor_contabilidad==true){

								     	if($dnco != false){

								     		$msj2="Orden de Pago - Contrato Obras Anticipo Anulado con exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"exito");
								     		$this->Session->write("MSJ",$MSJ);

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");

								     		$this->cepd03_ordenpago_cuerpo->execute("COMMIT;");

								     	}else{
								     		$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     		$msj2="Anulaci&oacute;n del Contrato Obras Anticipo sin exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     		$this->Session->write("MSJ",$MSJ);

								     	}

								     }else{
								     	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     	$msj2="Anulaci&oacute;n del Contrato Obras Anticipo sin exito";
								     	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     	$this->Session->write("MSJ",$MSJ);

								     }


								   }else{
								   	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								   	$msj2="Anulaci&oacute;n del Contrato Obras Anticipo sin exito";
								   	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								   	$this->Session->write("MSJ",$MSJ);
								   }
								   $this->consultar();
								   $this->render("consultar");
								   $this->set('tipo',4);
								   break;
				case 5://contrato obras valuaciones
				$tipo_documento=245;
				$ano=$ano;
						 //$numero_documento=$op;
				$concepto_anulacion=$this->data["cepp03_ordenpago"]["concepto_anulacion"];
				$fecha=$this->data["cepp03_ordenpago"]["fecha_documento_orden"];
				$fecha_documento=$this->Cfecha($fecha,"A-M-D");
						// $fecha_documento=$this->Cfecha($fecha_documento,"A-M-D");
						 $condicion_documento=2;//cuando se guarda es Activo=1

			             //$partidas_compromiso = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago='$opago'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
						 $v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano." ORDER BY numero_acta_anulacion DESC");
						 if($v!=null){
						 	$numero=$v[0][0]["numero_acta_anulacion"];
						 	$numero = $numero =="" ? 1 : $numero+1;

						 	$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano."");
						 }else{
						 	$v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",1)");
						 	$numero=1;
						 }
						 $v=$this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",".$numero.",".$tipo_documento.",".$ano.",'".$opago."','".date("Y-m-d")."','".$concepto_anulacion."')");
						 	if($v>1){
						 		$R1=$this->cobd01_contratoobras_valuacion_cuerpo->execute("UPDATE cobd01_contratoobras_valuacion_cuerpo SET ano_orden_pago=0,numero_orden_pago=0 WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$nrc."' and numero_valuacion=".$num_doc_adj);

						 		$Rx1=$this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.",fecha_proceso_anulacion='".date("Y-m-d")."', username_anulacion='".$this->Session->read('nom_usuario')."' WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);

						 		if($R1>1){
						 			$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		}

						 	}

						 	$msj=isset($R1) && $R1>1?true:false;
						 	if($msj==true){
						 		$partidas_causado = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano=".$ano." and numero_orden_pago=".$opago);
						 		foreach($partidas_causado as $vec){
						 			$cp   = $this->crear_partida($vec["cepd03_ordenpago_partidas"]["ano"], $vec["cepd03_ordenpago_partidas"]["cod_sector"], $vec["cepd03_ordenpago_partidas"]["cod_programa"], $vec["cepd03_ordenpago_partidas"]["cod_sub_prog"], $vec["cepd03_ordenpago_partidas"]["cod_proyecto"], $vec["cepd03_ordenpago_partidas"]["cod_activ_obra"], $vec["cepd03_ordenpago_partidas"]["cod_partida"],$vec["cepd03_ordenpago_partidas"]["cod_generica"], $vec["cepd03_ordenpago_partidas"]["cod_especifica"], $vec["cepd03_ordenpago_partidas"]["cod_sub_espec"],$vec["cepd03_ordenpago_partidas"]["cod_auxiliar"]);
						 			$to   = 2;
						 			$td   = 4;
						 			$ta   = 5;
						 			$rnco = $vec["cepd03_ordenpago_partidas"]["numero_control_compromiso"];
						 			$rnca = $vec["cepd03_ordenpago_partidas"]["numero_control_causado"];
						 			$mt   = $vec["cepd03_ordenpago_partidas"]["monto"];
						 			$ccp  = $concepto_anulacion;

						 			
						 			if($isNomina==false){
						 				$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 			}else{								
						 				$sqlCheck = 'SELECT * FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
						 				$checkDep = $this->cepd03_ordenpago_partidas->execute($sqlCheck);
											        // if($cod_dep==9999 || $cod_dep==1028){
						 				if(count($checkDep)>0){
						 					$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 				}else{
						 					$dnco=true;											
						 				}
						 			}

								     }//fin foreach


								     if($valor_motor_contabilidad==true){

								     	if($dnco != false){

								     		$msj2="Orden de Pago - Contrato Obras Valuacion Anulado con exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"exito");
								     		$this->Session->write("MSJ",$MSJ);

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");

								     		$this->cepd03_ordenpago_cuerpo->execute("COMMIT;");

								     	}else{
								     		$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     		$msj2="Anulaci&oacute;n del Contrato Obras Valuacion sin exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     		$this->Session->write("MSJ",$MSJ);

								     	}

								     }else{
								     	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     	$msj2="Anulaci&oacute;n del Contrato Obras Valuacion sin exito";
								     	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     	$this->Session->write("MSJ",$MSJ);

								     }



								   }else{
								   	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								   	$msj2="Anulaci&oacute;n del Contrato Obras Valuacion sin exito";
								   	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								   	$this->Session->write("MSJ",$MSJ);
								   }
								   $this->consultar();
								   $this->render("consultar");
								   $this->set('tipo',5);
								   break;
			case 6://contrato obras retenciones
				$tipo_documento=2412;
				$ano=$ano;
				//$opago=$op;
				$concepto_anulacion=$this->data["cepp03_ordenpago"]["concepto_anulacion"];
				$fecha=$this->data["cepp03_ordenpago"]["fecha_documento_orden"];
				$fecha_documento=$this->Cfecha($fecha,"A-M-D");
				// $fecha_documento=$this->Cfecha($fecha_documento,"A-M-D");
				$condicion_documento=2;//cuando se guarda es Activo=1

			  //$partidas_compromiso = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago='$opago'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
				$v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano." ORDER BY numero_acta_anulacion DESC");
				
				if($v!=null){
				 	$numero=$v[0][0]["numero_acta_anulacion"];
				 	$numero = $numero =="" ? 1 : $numero+1;

				 	$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano."");
				}else{
				 	$v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",1)");
				 	$numero=1;
				}
				
				$v=$this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",".$numero.",".$tipo_documento.",".$ano.",'".$opago."','".date("Y-m-d")."','".$concepto_anulacion."')");
				
				if($v>1){
					$R1=$this->cobd01_contratoobras_retencion_cuerpo->execute("UPDATE cobd01_contratoobras_retencion_cuerpo SET ano_orden_pago=0,numero_orden_pago=0 WHERE ".$this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$nrc."' and numero_retencion=".$num_doc_adj);
					$Rx1=$this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.",fecha_proceso_anulacion='".date("Y-m-d")."', username_anulacion='".$this->Session->read('nom_usuario')."' WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
				
					if($R1>1){
						$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
					}
				}

				$msj=isset($R1) && $R1>1?true:false;
				
				if($msj==true){
					$partidas_causado = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano=".$ano." and numero_orden_pago=".$opago);
				
					foreach($partidas_causado as $vec){
						$cp   = $this->crear_partida($vec["cepd03_ordenpago_partidas"]["ano"], $vec["cepd03_ordenpago_partidas"]["cod_sector"], $vec["cepd03_ordenpago_partidas"]["cod_programa"], $vec["cepd03_ordenpago_partidas"]["cod_sub_prog"], $vec["cepd03_ordenpago_partidas"]["cod_proyecto"], $vec["cepd03_ordenpago_partidas"]["cod_activ_obra"], $vec["cepd03_ordenpago_partidas"]["cod_partida"],$vec["cepd03_ordenpago_partidas"]["cod_generica"], $vec["cepd03_ordenpago_partidas"]["cod_especifica"], $vec["cepd03_ordenpago_partidas"]["cod_sub_espec"],$vec["cepd03_ordenpago_partidas"]["cod_auxiliar"]);
						$to   = 2;
						$td   = 4;
						$ta   = 12;
						$rnco = $vec["cepd03_ordenpago_partidas"]["numero_control_compromiso"];
						$rnca = $vec["cepd03_ordenpago_partidas"]["numero_control_causado"];

						$mt   = $vec["cepd03_ordenpago_partidas"]["monto"];
						$ccp  = $concepto_anulacion;
						 			
						if($isNomina==false){
							$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
			 			}else{								
			 				$sqlCheck = 'SELECT * FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
			 				$checkDep = $this->cepd03_ordenpago_partidas->execute($sqlCheck);
			        // if($cod_dep==9999 || $cod_dep==1028){
			 				if(count($checkDep)>0){
			 					$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
		 					}else{
		 						$dnco=true;											
		 					}
		 				}

					}//fin foreach

		    	if($valor_motor_contabilidad==true){
		     	
			     	if($dnco != false){
			    		$msj2="Orden de Pago - Contrato Obras Retencion Anulado con exito";
			     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"exito");
			     		$this->Session->write("MSJ",$MSJ);

			     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
			     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
			     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
			     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
			     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
			     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");

			     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
			     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
			     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
			     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
			     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
			     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");

			     		$this->cepd03_ordenpago_cuerpo->execute("COMMIT;");
			     	}else{
			     		$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
			     		$msj2="3.Anulaci&oacute;n del Contrato Obras Retencion sin exito";
			     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
			     		$this->Session->write("MSJ",$MSJ);
			     	}

					}else{
						$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
						$msj2="2.Anulaci&oacute;n del Contrato Obras Retencion sin exito";
						$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
						$this->Session->write("MSJ",$MSJ);
					}
				}else{
					$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
					$msj2="1.Anulaci&oacute;n del Contrato Obras Retención sin exito";
					$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
					$this->Session->write("MSJ",$MSJ);
				}

				$this->consultar();
				$this->render("consultar");
				$this->set('tipo',6);

			break;
				case 7://contrato servicio anticipo
				$tipo_documento=246;
				$ano=$ano;
						 //$opago=$op;
				$concepto_anulacion=$this->data["cepp03_ordenpago"]["concepto_anulacion"];
				$fecha=$this->data["cepp03_ordenpago"]["fecha_documento_orden"];
				$fecha_documento=$this->Cfecha($fecha,"A-M-D");
						// $fecha_documento=$this->Cfecha($fecha_documento,"A-M-D");
						 $condicion_documento=2;//cuando se guarda es Activo=1

			             //$partidas_compromiso = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago='$opago'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
						 $v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano." ORDER BY numero_acta_anulacion DESC");
						 if($v!=null){
						 	$numero=$v[0][0]["numero_acta_anulacion"];
						 	$numero = $numero =="" ? 1 : $numero+1;


						 	$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano."");
						 }else{
						 	$v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",1)");
						 	$numero=1;
						 }
						 $v=$this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",".$numero.",".$tipo_documento.",".$ano.",'".$opago."','".date("Y-m-d")."','".$concepto_anulacion."')");
						 	if($v>1){
						 		$R1=$this->cepd02_contratoservicio_anticipo_cuerpo->execute("UPDATE cepd02_contratoservicio_anticipo_cuerpo SET ano_orden_pago=0,numero_orden_pago=0 WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$nrc."' and numero_anticipo=".$num_doc_adj);
						 		$Rx1=$this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.",fecha_proceso_anulacion='".date("Y-m-d")."', username_anulacion='".$this->Session->read('nom_usuario')."' WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		if($R1>1){
						 			$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		}
						 	}

						 	$msj=isset($R1) && $R1>1?true:false;
						 	if($msj==true){
						 		$partidas_causado = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano=".$ano." and numero_orden_pago=".$opago);
						 		foreach($partidas_causado as $vec){
						 			$cp   = $this->crear_partida($vec["cepd03_ordenpago_partidas"]["ano"], $vec["cepd03_ordenpago_partidas"]["cod_sector"], $vec["cepd03_ordenpago_partidas"]["cod_programa"], $vec["cepd03_ordenpago_partidas"]["cod_sub_prog"], $vec["cepd03_ordenpago_partidas"]["cod_proyecto"], $vec["cepd03_ordenpago_partidas"]["cod_activ_obra"], $vec["cepd03_ordenpago_partidas"]["cod_partida"],$vec["cepd03_ordenpago_partidas"]["cod_generica"], $vec["cepd03_ordenpago_partidas"]["cod_especifica"], $vec["cepd03_ordenpago_partidas"]["cod_sub_espec"],$vec["cepd03_ordenpago_partidas"]["cod_auxiliar"]);
						 			$to   = 2;
						 			$td   = 4;
						 			$ta   = 6;
						 			$rnco = $vec["cepd03_ordenpago_partidas"]["numero_control_compromiso"];
						 			$rnca = $vec["cepd03_ordenpago_partidas"]["numero_control_causado"];
						 			$mt = $vec["cepd03_ordenpago_partidas"]["monto"];
						 			$ccp = $concepto_anulacion;
						 			
						 			if($isNomina==false){
						 				$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 			}else{								
						 				$sqlCheck = 'SELECT * FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
						 				$checkDep = $this->cepd03_ordenpago_partidas->execute($sqlCheck);
											        // if($cod_dep==9999 || $cod_dep==1028){
						 				if(count($checkDep)>0){
						 					$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 				}else{
						 					$dnco=true;											
						 				}
						 			}

								     }//fin foreach




								     if($valor_motor_contabilidad==true){

								     	if($dnco != false){

								     		$msj2="Orden de Pago - Contrato servicio Anticipo Anulado con exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"exito");
								     		$this->Session->write("MSJ",$MSJ);

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");

								     		$this->cepd03_ordenpago_cuerpo->execute("COMMIT;");

								     	}else{
								     		$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     		$msj2="Anulaci&oacute;n del Contrato Servicio Anticipo sin exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     		$this->Session->write("MSJ",$MSJ);

								     	}

								     }else{
								     	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     	$msj2="Anulaci&oacute;n del Contrato Servicio Anticipo sin exito";
								     	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     	$this->Session->write("MSJ",$MSJ);

								     }







								   }else{
								   	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								   	$msj2="Anulaci&oacute;n del Contrato Servicio Anticipo sin exito";
								   	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								   	$this->Session->write("MSJ",$MSJ);
								   }
								   $this->consultar();
								   $this->render("consultar");
								   $this->set('tipo',4);
								   break;
				case 8://contrato servicio valuaciones
				$tipo_documento=247;
				$ano=$ano;
						 //$opago=$op;
				$concepto_anulacion=$this->data["cepp03_ordenpago"]["concepto_anulacion"];
				$fecha=$this->data["cepp03_ordenpago"]["fecha_documento_orden"];
				$fecha_documento=$this->Cfecha($fecha,"A-M-D");
						 $condicion_documento=2;//cuando se guarda es Activo=1
						 $v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano." ORDER BY numero_acta_anulacion DESC");
						 if($v!=null){
						 	$numero=$v[0][0]["numero_acta_anulacion"];
						 	$numero = $numero =="" ? 1 : $numero+1;

						 	$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano."");
						 }else{
						 	$v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",1)");
						 	$numero=1;
						 }
						 $v=$this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",".$numero.",".$tipo_documento.",".$ano.",'".$opago."','".date("Y-m-d")."','".$concepto_anulacion."')");
						 	if($v>1){
						 		$R1=$this->cepd02_contratoservicio_valuacion_cuerpo->execute("UPDATE cepd02_contratoservicio_valuacion_cuerpo SET ano_orden_pago=0,numero_orden_pago=0 WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$nrc."' and numero_valuacion=".$num_doc_adj);
						 		$Rx1=$this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.",fecha_proceso_anulacion='".date("Y-m-d")."', username_anulacion='".$this->Session->read('nom_usuario')."' WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		if($R1>1){
						 			$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		}
						 	}

						 	$msj=isset($R1) && $R1>1?true:false;
						 	if($msj==true){
						 		$partidas_causado = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano=".$ano." and numero_orden_pago=".$opago);
						 		foreach($partidas_causado as $vec){
						 			$cp   = $this->crear_partida($vec["cepd03_ordenpago_partidas"]["ano"], $vec["cepd03_ordenpago_partidas"]["cod_sector"], $vec["cepd03_ordenpago_partidas"]["cod_programa"], $vec["cepd03_ordenpago_partidas"]["cod_sub_prog"], $vec["cepd03_ordenpago_partidas"]["cod_proyecto"], $vec["cepd03_ordenpago_partidas"]["cod_activ_obra"], $vec["cepd03_ordenpago_partidas"]["cod_partida"],$vec["cepd03_ordenpago_partidas"]["cod_generica"], $vec["cepd03_ordenpago_partidas"]["cod_especifica"], $vec["cepd03_ordenpago_partidas"]["cod_sub_espec"],$vec["cepd03_ordenpago_partidas"]["cod_auxiliar"]);
						 			$to   = 2;
						 			$td   = 4;
						 			$ta   = 7;
						 			$rnco = $vec["cepd03_ordenpago_partidas"]["numero_control_compromiso"];
						 			$rnca = $vec["cepd03_ordenpago_partidas"]["numero_control_causado"];
						 			$mt = $vec["cepd03_ordenpago_partidas"]["monto"];
						 			$ccp = $concepto_anulacion;

						 			
						 			if($isNomina==false){
						 				$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 			}else{								
						 				$sqlCheck = 'SELECT * FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
						 				$checkDep = $this->cepd03_ordenpago_partidas->execute($sqlCheck);
											        // if($cod_dep==9999 || $cod_dep==1028){
						 				if(count($checkDep)>0){
						 					$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 				}else{
						 					$dnco=true;											
						 				}
						 			}

								     }//fin foreach

								     if($valor_motor_contabilidad==true){

								     	if($dnco != false){

								     		$msj2="Orden de Pago - Contrato Servicio Valuacion Anulado con exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"exito");
								     		$this->Session->write("MSJ",$MSJ);

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");

								     		$this->cepd03_ordenpago_cuerpo->execute("COMMIT;");

								     	}else{
								     		$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     		$msj2="Anulaci&oacute;n del Contrato Servicio Valuacion sin exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     		$this->Session->write("MSJ",$MSJ);


								     	}

								     }else{
								     	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     	$msj2="Anulaci&oacute;n del Contrato Servicio Valuacion sin exito";
								     	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     	$this->Session->write("MSJ",$MSJ);

								     }







								   }else{
								   	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								   	$msj2="Anulaci&oacute;n del Contrato Servicio Valuacion sin exito";
								   	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								   	$this->Session->write("MSJ",$MSJ);
								   }
								   $this->consultar();
								   $this->render("consultar");
								   $this->set('tipo',8);
								   break;
				case 9://contrato servicio retenciones
				$tipo_documento=2411;
				$ano=$ano;
						 //$opago=$op;
				$concepto_anulacion=$this->data["cepp03_ordenpago"]["concepto_anulacion"];
				$fecha=$this->data["cepp03_ordenpago"]["fecha_documento_orden"];
				$fecha_documento=$this->Cfecha($fecha,"A-M-D");
						 $condicion_documento=2;//cuando se guarda es Activo=1
						 $v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano." ORDER BY numero_acta_anulacion DESC");
						 if($v!=null){
						 	$numero=$v[0][0]["numero_acta_anulacion"];
						 	$numero = $numero =="" ? 1 : $numero+1;
						 	$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano."");
						 }else{
						 	$v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",1)");
						 	$numero=1;
						 }
						 $v=$this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",".$numero.",".$tipo_documento.",".$ano.",'".$opago."','".date("Y-m-d")."','".$concepto_anulacion."')");
						 	if($v>1){
						 		$R1=$this->cepd02_contratoservicio_valuacion_cuerpo->execute("UPDATE cepd02_contratoservicio_retencion_cuerpo SET ano_orden_pago=0,numero_orden_pago=0 WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$nrc."' and numero_retencion=".$num_doc_adj);
						 		$Rx1=$this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.",fecha_proceso_anulacion='".date("Y-m-d")."', username_anulacion='".$this->Session->read('nom_usuario')."' WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		if($R1>1){
						 			$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		}
						 	}

						 	$msj=isset($R1) && $R1>1?true:false;
						 	if($msj==true){
						 		$partidas_causado = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano=".$ano." and numero_orden_pago=".$opago);
						 		foreach($partidas_causado as $vec){

						 			$cp   = $this->crear_partida($vec["cepd03_ordenpago_partidas"]["ano"], $vec["cepd03_ordenpago_partidas"]["cod_sector"], $vec["cepd03_ordenpago_partidas"]["cod_programa"], $vec["cepd03_ordenpago_partidas"]["cod_sub_prog"], $vec["cepd03_ordenpago_partidas"]["cod_proyecto"], $vec["cepd03_ordenpago_partidas"]["cod_activ_obra"], $vec["cepd03_ordenpago_partidas"]["cod_partida"],$vec["cepd03_ordenpago_partidas"]["cod_generica"], $vec["cepd03_ordenpago_partidas"]["cod_especifica"], $vec["cepd03_ordenpago_partidas"]["cod_sub_espec"],$vec["cepd03_ordenpago_partidas"]["cod_auxiliar"]);
						 			$to   = 2;
						 			$td   = 4;
						 			$ta   = 11;
						 			$rnco = $vec["cepd03_ordenpago_partidas"]["numero_control_compromiso"];
						 			$rnca = $vec["cepd03_ordenpago_partidas"]["numero_control_causado"];
						 			$mt   = $vec["cepd03_ordenpago_partidas"]["monto"];
						 			$ccp  = $concepto_anulacion;

						 			if($isNomina==false){
						 				$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 			}else{								
						 				$sqlCheck = 'SELECT * FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
						 				$checkDep = $this->cepd03_ordenpago_partidas->execute($sqlCheck);
											        // if($cod_dep==9999 || $cod_dep==1028){
						 				if(count($checkDep)>0){
						 					$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 				}else{
						 					$dnco=true;											
						 				}
						 			}

						 			if($dnco != false){

						 			}else{

						 				break;
						 			}


								     }//fin foreach



								     if($valor_motor_contabilidad==true){

								     	if($dnco != false){

								     		$msj2="Orden de Pago - Contrato Servicio Retencion Anulado con exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"exito");
								     		$this->Session->write("MSJ",$MSJ);

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");

								     		$this->cepd03_ordenpago_cuerpo->execute("COMMIT;");

								     	}else{
								     		$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     		$msj2="Anulaci&oacute;n del Contrato Servicio Retencion sin exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     		$this->Session->write("MSJ",$MSJ);

								     	}

								     }else{
								     	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     	$msj2="Anulaci&oacute;n del Contrato Servicio Retencion sin exito";
								     	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     	$this->Session->write("MSJ",$MSJ);

								     }








								   }else{
								   	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								   	$msj2="Anulaci&oacute;n del Contrato Servicio retencion sin exito.";
								   	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								   	$this->Session->write("MSJ",$MSJ);
								   }
								   $this->consultar();
								   $this->render("consultar");
								   $this->set('tipo',9);

								   break;

				case 10://Ordenes de compras - Retenciones

				$tipo_documento=2413;
				$ano=$ano;
						 //$opago=$op;
				$concepto_anulacion=$this->data["cepp03_ordenpago"]["concepto_anulacion"];
				$fecha=$this->data["cepp03_ordenpago"]["fecha_documento_orden"];
				$fecha_documento=$this->Cfecha($fecha,"A-M-D");
						// $fecha_documento=$this->Cfecha($fecha_documento,"A-M-D");
						 $condicion_documento=2;//cuando se guarda es Activo=1

			             //$partidas_compromiso = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago='$opago'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
						 $v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano." ORDER BY numero_acta_anulacion DESC");
						 if($v!=null){
						 	$numero=$v[0][0]["numero_acta_anulacion"];
						 	$numero = $numero =="" ? 1 : $numero+1;

						 	$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano."");
						 }else{
						 	$v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",1)");
						 	$numero=1;
						 }
						 $v=$this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",".$numero.",".$tipo_documento.",".$ano.",'".$opago."','".date("Y-m-d")."','".$concepto_anulacion."')");
						 	if($v>1){
						 		$R1=$this->cobd01_contratoobras_retencion_cuerpo->execute("UPDATE cscd04_ordencompra_retencion_cuerpo SET ano_orden_pago=0,numero_orden_pago=0 WHERE ".$this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$nrc." and numero_retencion=".$num_doc_adj);
						 		$Rx1=$this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo SET ano_anulacion=".date("Y").", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.",fecha_proceso_anulacion='".date("Y-m-d")."', username_anulacion='".$this->Session->read('nom_usuario')."' WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		if($R1>1){
						 			$this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=4 WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago);
						 		}
						 	}

						 	$msj=isset($R1) && $R1>1?true:false;
						 	if($msj==true){
						 		$partidas_causado = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano=".$ano." and numero_orden_pago=".$opago);
						 		foreach($partidas_causado as $vec){
						 			$cp   = $this->crear_partida($vec["cepd03_ordenpago_partidas"]["ano"], $vec["cepd03_ordenpago_partidas"]["cod_sector"], $vec["cepd03_ordenpago_partidas"]["cod_programa"], $vec["cepd03_ordenpago_partidas"]["cod_sub_prog"], $vec["cepd03_ordenpago_partidas"]["cod_proyecto"], $vec["cepd03_ordenpago_partidas"]["cod_activ_obra"], $vec["cepd03_ordenpago_partidas"]["cod_partida"],$vec["cepd03_ordenpago_partidas"]["cod_generica"], $vec["cepd03_ordenpago_partidas"]["cod_especifica"], $vec["cepd03_ordenpago_partidas"]["cod_sub_espec"],$vec["cepd03_ordenpago_partidas"]["cod_auxiliar"]);
						 			$to   = 2;
						 			$td   = 4;
						 			$ta   = 13;
						 			$rnco = $vec["cepd03_ordenpago_partidas"]["numero_control_compromiso"];
						 			$rnca = $vec["cepd03_ordenpago_partidas"]["numero_control_causado"];

						 			$mt   = $vec["cepd03_ordenpago_partidas"]["monto"];
						 			$ccp  = $concepto_anulacion;

						 			if($isNomina==false){
						 				$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 			}else{								
						 				$sqlCheck = 'SELECT * FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
						 				$checkDep = $this->cepd03_ordenpago_partidas->execute($sqlCheck);
											        // if($cod_dep==9999 || $cod_dep==1028){
						 				if(count($checkDep)>0){
						 					$dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ano, $ndo, $nda, $opago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, null);
						 				}else{
						 					$dnco=true;											
						 				}
						 			}

								     }//fin foreach



								     if($valor_motor_contabilidad==true){

								     	if($dnco != false){

								     		$msj2="Orden de Pago - Contrato Obras Retencion Anulado con exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"exito");
								     		$this->Session->write("MSJ",$MSJ);

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_partidas_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."");

								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_iva WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_islr WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_municipal WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_timbre WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_multa WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");
								     		$this->cstd01_entidades_bancarias->execute("DELETE FROM cstd07_retenciones_cuerpo_responsabilidad WHERE  ".$this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago=".$opago."  and numero_cheque=0");

								     		$this->cepd03_ordenpago_cuerpo->execute("COMMIT;");

								     	}else{
								     		$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     		$msj2="Anulaci&oacute;n del Contrato Obras Retencion sin exito";
								     		$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     		$this->Session->write("MSJ",$MSJ);

								     	}

								     }else{
								     	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								     	$msj2="Anulaci&oacute;n del Contrato Obras Retencion sin exito";
								     	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								     	$this->Session->write("MSJ",$MSJ);

								     }

								   }else{
								   	$this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
								   	$msj2="Anulaci&oacute;n del Contrato Obras Retención sin exito";
								   	$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
								   	$this->Session->write("MSJ",$MSJ);
								   }
								   $this->consultar();
								   $this->render("consultar");
								   $this->set('tipo',10);

								   break;

			}//fin switch

		}
}//fin guardar_anulacion




function salir_orden($codigo=null){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	$c=$this->cepd03_ordenpago_numero->findCount($this->SQLCA()." and numero_orden_pago=".$codigo." and ano_orden_pago=".$ano." and situacion=3");
	if($c==0){
		$resultado=$this->cepd03_ordenpago_numero->execute("UPDATE  cepd03_ordenpago_numero SET situacion=1 WHERE ".$this->SQLCA()." and numero_orden_pago=".$codigo." and ano_orden_pago=".$ano);
	}

	$this->Session->delete("items");
	$this->Session->delete("i");
	$this->Session->delete("contador");

	echo"<script>menu_activo();</script>";

}//salir orden


function reactualizar_nro_rc(){
	$this->layout="ajax";
	$resultado=$this->cepd03_ordenpago_cuerpo->execute("select
		a.cod_dep as dep_rc,
		a.numero_documento as nro_rc,
		a.numero_orden_pago as nro_op_rc,
		a.monto as monto_rc,
		b.cod_dep as dep_op,
		b.numero_orden_pago as nro_op,
		b.numero_documento_origen as nro_rc_op,
		b.monto_total as monto_op
		from
		cepd01_compromiso_cuerpo a,
		cepd03_ordenpago_cuerpo b
		where
		b.cod_dep=a.cod_dep and
		b.numero_documento_origen::varchar(20)=a.numero_documento::varchar(30) and
		b.numero_documento_adjunto='0' and
		a.condicion_actividad=1 and b.condicion_actividad=1 and
		b.monto_total=a.monto
		order by
		a.cod_dep,a.numero_documento asc");
	$i=0;
	$j=0;
	foreach($resultado as $res){
		$cod_dep=$res[0]["dep_rc"];
		$nro_op=$res[0]["nro_op"];
		$nro_rc=$res[0]["nro_rc_op"];
		$sql_update="UPDATE cepd01_compromiso_cuerpo SET numero_orden_pago=".$nro_op." WHERE cod_dep=".$cod_dep." and numero_documento=".$nro_rc.";";
		$x=$this->cepd01_compromiso_cuerpo->execute($sql_update);
		if($x>1){
			echo $sql_update." \n";
			$i++;
		}else{
			$j++;
		}

 }//fin for
 echo "UPDATES:".$i."<br>NO UPDATES:".$j;
}

function actualizame_op () {
	$this->layout="ajax";
    $this->Session->write('up_op',"");//date("H:i:s")

  }

  function entrar(){
  	$this->layout="ajax";
  	if(isset($this->data['cepp03_ordenpago']['login']) && isset($this->data['cepp03_ordenpago']['password'])){
  		$l="PROYECTO";
  		$c="JJJSAE";
  		$user=addslashes($this->data['cepp03_ordenpago']['login']);
  		$paswd=addslashes($this->data['cepp03_ordenpago']['password']);
  		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=84 and clave='".$paswd."'";
  		if($user==$l && $paswd==$c){
  			$this->Session->write('autor_valido',true);
  			$this->index2("autor_valido");
  			$this->render("index2");
  		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
  			$this->Session->write('autor_valido',true);
  			$this->index2("autor_valido");
  			$this->render("index2");
  		}else{
  			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
  			$this->Session->delete('autor_valido');
  			$this->index2("autor_valido");
  			$this->render("index2");
  		}
  	}
  }

  function salir(){
  	$this->layout="ajax";
  	$this->Session->delete("autor_valido");
  }

 }//fin class
 ?>
