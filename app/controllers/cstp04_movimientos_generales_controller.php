<?php
/*
 * Creado el  08/12/2007 a las 09:14:46 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
class Cstp04MovimientosGeneralesController extends AppController {
 	var $name = 'cstp04_movimientos_generales';
 	var $uses = array ('v_cstd01_bancos','v_cstd01_sucursales','cstd03_movimientos_manuales', 'cstd04_movimientos_generales', 'cstd02_cuentas_bancarias', 'cstd01_entidades_bancarias', 'cstd01_sucursales_bancarias','ccfd03_instalacion','cstd03_cheque_numero','cugd03_acta_anulacion_numero','cugd03_acta_anulacion_cuerpo','ccfd04_cierre_mes','cstd04_cheque_poremitir','cstd06_comprobante_numero_egreso','cstd06_comprobante_cuerpo_egreso','v_cstd_mov_gral', 'cstd03_cheque_cuerpo', 'cugd07_firmas_oficio_anulacion', 'arrd05', 'cstd09_notadebito_cuerpo', 'cstd09_notadebito_cuerpo_pago','v_cstd04_movimientos_generales','cstd05_estado_cuentas','v_cstd05_estado_cuenta_vs_tesoreria', 'v_cstd05_tesoreria_vs_estado_cuenta', 'v_cstd05_estado_cuenta_no_tesoreria','v_cstd05_tesoreria_no_estado_cuenta');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');



function beforeFilter(){
	$this->checkSession();
}

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


function mascara3($cod){
	$opc = strlen($cod);
	switch ($opc) {
		case 1:
			$cod = '000'.$cod;
			break;
		case 2:
			$cod = '00'.$cod;
			break;
		case 3:
			$cod = '0'.$cod;
			break;

		default:
			break;
	}

	return $cod;
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


function index () {
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
 	$this->set('vector_cuenta','');
 	$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'direccion_superior');

  //$direccion_superior=  $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
  //$this->concatena($direccion_superior, 'direccion_superior');

	$ano = $this->ano_ejecucion();
    $this->set('operador_1', $this->Session->read('nom_usuario'));
	$this->set('ano_movimiento', $ano);
}


function select3($select=null,$var=null,$var2=null) { //select codigos
	$this->layout = "ajax";

	if($var!=null){
    $cond =$this->SQLCA();//vario
	switch($select){
		case 'entidad_bancaria':
		  $this->set('SELECT','coordinacion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',1);
		break;
		case 'sucursal':
		  $this->set('SELECT','secretaria');//El parametro que se le pasa para que busque el proximo select (cuando entre en select3)
		  $this->set('codigo','sucursal');//El nombre que se le asigna al select actual cuando se crea
		  $this->set('cod_sucursal',$var);//cod_sucursal es para mantener el valor de la variable que llega y pasarselo al paso que viene en select3
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->set('no','no');
		  $this->set('codigo_entidad',$var);
		  $this->set('codigo_sucursal',$var2);
		  $cond ="cod_entidad_bancaria=".$var;
		  $busca=$this->SQLCA()." and cod_entidad_bancaria=".$var;
		  $this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'vector');

/*
		  $sucursales = $this->cstd01_sucursales_bancarias->findAll($cond, null, 'cod_sucursal ASC');
		  $lista=array();
		  $codSucursal  = array();
		  $denoSucursal = array();
	      $total_sucursales = count($sucursales);
		  if($total_sucursales==0){
		  		$lista = array();
		  }else{
		  		for($i=0; $i<$total_sucursales; $i++){
		  			$codSucursal[]  = mascara($sucursales[$i]['cstd01_sucursales_bancarias']['cod_sucursal'],4);
					$denoSucursal[] = mascara($sucursales[$i]['cstd01_sucursales_bancarias']['cod_sucursal'],4)." - ".$sucursales[$i]['cstd01_sucursales_bancarias']['denominacion'];
		  		}
		  		$lista = array_combine($codSucursal, $denoSucursal);
		  }
		  $this->set('vector',$lista);
   		  //$this->concatena($lista, 'vector');

*/

		break;
		case 'secretaria':
		  // $this->set('SELECT','direccion');
		  // $this->set('codigo','secretaria');
		  // $this->set('seleccion','');
		  // this->set('n',3);
		  // echo $select."- - - 1.<br>";
		  // echo $var."- - - 1.<br>";
		  // $lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
          // $this->concatena($lista, 'vector');
		break;
	}//fin switch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('no','no');
		  $this->set('vector','');
	}
}//fin select codigos




function mostrar4($select=null,$var=null,$var2=null) {
	$this->layout = "ajax";

    if( $var!=null){
	switch($select){
		case 'entidad_bancaria':
		  $cond ="cod_entidad_bancaria=".$var;
		  $a=  $this->cstd01_entidades_bancarias->findAll($cond);
          echo "<input type='text' name='data[cstp04_movimientos_generales][cod_entidad_bancaria]' value='".$this->mascara3($a[0]['cstd01_entidades_bancarias']['cod_entidad_bancaria'])."' size='5'  maxlength='4' id='cod_entidad_bancaria' readonly='readonly' class='inputtext' style='text-align:center' />";
		break;
		case 'sucursal':
		    if(!isset($var) || !isset($var2)){
		  	    echo "<input type='text' name='data[cstp04_movimientos_generales][cod_sucursal_bancaria]' value='' size='5' maxlength='4' id='cod_sucursal_bancaria' readonly='readonly' class='inputtext' style='text-align:center' />";
		    }else{
		    $cond ="cod_entidad_bancaria=".$var." and cod_sucursal=".$var2;
		    $a=  $this->cstd01_sucursales_bancarias->findAll($cond);
            echo "<input type='text' name='data[cstp04_movimientos_generales][cod_sucursal_bancaria]' value='".$this->mascara3($a[0]['cstd01_sucursales_bancarias']['cod_sucursal'])."' id='cod_sucursal_bancaria' size='5' maxlength='4'  readonly='readonly' class='inputtext' style='text-align:center' />";
		    }
		    break;
		case 'secretaria':
		  // $ano =  $this->Session->read('ano');
		  // $ddirs =  $this->Session->read('ddirs');
		  // $dcoor =  $this->Session->read('dcoor');
		  // $this->Session->write('dsecr',$var);
		  // $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$var;
		  // $a=  $this->cugd02_secretaria->findAll($cond);
          // echo $a[0]['cugd02_secretaria']['cod_secretaria']>9 ?$a[0]['cod_secretaria']['cod_coordinacion'] : "0".$a[0]['cugd02_secretaria']['cod_secretaria'];
		break;
	  }//fin switch
	}else{
		echo "<input type='text' name='data[cstp04_movimientos_generales]' size='5' maxlength='4' id='cod_entidad_bancaria' class='inputtext' style='text-align:center' />";
	}
}//fin mostrar4 codigos





function mostrar3($select=null,$var=null,$var2=null) {
	$this->layout = "ajax";
if( $var!=null && !empty($var)){
	switch($select){
		case 'entidad_bancaria':
		  $cond ="cod_entidad_bancaria=".$var;
		  $a=  $this->cstd01_entidades_bancarias->findAll($cond);
          echo "<input type='text' name='data[cstp04_movimientos_generales][deno_entidad_bancaria]' value='".$a[0]['cstd01_entidades_bancarias']['denominacion']."' maxlength='100' id='deno_entidad_bancaria' class='inputtext' />";
		break;
		case 'sucursal':
		    if(!isset($var2)){
		  	    echo "<input type='text' name='data[cstp04_movimientos_generales][deno_sucursal_bancaria]' value='' maxlength='100' id='deno_sucursal_bancaria' class='inputtext' />";
		    }else{
		    $cond ="cod_entidad_bancaria=".$var." and cod_sucursal=".$var2;
		    $a=  $this->cstd01_sucursales_bancarias->findAll($cond);
            echo "<input type='text' name='data[cstp04_movimientos_generales][deno_sucursal_bancaria]' value='".$a[0]['cstd01_sucursales_bancarias']['denominacion']."' maxlength='100' id='deno_sucursal_bancaria' class='inputtext' />";
		    }
		   break;
		case 'secretaria':
		  // $ano =  $this->Session->read('ano');
		  // $ddirs =  $this->Session->read('ddirs');
		  // $dcoor =  $this->Session->read('dcoor');
		  // $this->Session->write('dsecr',$var);
		  // $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$var;
		  // $a=  $this->cugd02_secretaria->findAll($cond);
          // echo $a[0]['cugd02_secretaria']['denominacion'];
		break;
	}//fin switch
	}else{
		echo "<input type='text' name='data[cstp04_movimientos_generales] value='' size='37'  maxlength='100' />";
	}
}//fin mostrar3 denominaciones




function mostrar5($select=null,$var=null,$var2=null, $cod_ent=null, $cod_sucursa=null) {
	$this->layout = "ajax";
    if(!isset($var2)){
		$this->set('vector_cuenta','');
		$this->set('lista','');
    }else{
		$cond = $this->SQLCA()." and cod_entidad_bancaria =".$var." and cod_sucursal=".$var2;
	    $lista = $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
	    $this->set('vector_cuenta',$lista);
	    $this->set('codigo',$var);
	    $this->set('cod_sucursal',$var2);
    }
}



function generar_reporte_mov_general(){
	$this->layout = "pdf";

	$ano_movimiento = $this->data['cstp04_movimientos_generales']['ano_1'];
	$cod_entidad_bancaria = $this->data['cstp04_movimientos_generales']['cod_entidad_bancaria'];
	$cod_sucursal = $this->data['cstp04_movimientos_generales']['cod_sucursal_bancaria'];
	$cuenta_bancaria =$this->data['cstp04_movimientos_generales']['cuenta_bancaria'];
	/////////////////////////////////////////////////////////////////////////$tipo_documento =$this->data['cstp04_movimientos_generales']['tipo_documento'];
	$por_ano =$this->data['cstp04_movimientos_generales']['por_ano'];
	if($por_ano==2){
		$selectmes =$this->data['cstp04_movimientos_generales']['selectmes'];
		$this->set('selectmes', $selectmes);
	}

	$ent = $this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad_bancaria);
	$suc = $this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal);
	$concep_manejo_cuenta = $this->cstd02_cuentas_bancarias->findAll($this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'", array('concepto_manejo'));

	$cd=$this->Session->read('SScoddep');
	$ano=$this->ano_ejecucion();
	$condicion=$this->SQLCA()." and ano_movimiento='$ano' and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'";

	$vista=$this->v_cstd_mov_gral->findAll($this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'",null,'dia, mes, ano_movimiento, tipo_documento, numero_documento ASC');
	$this->set('vista',$vista);
	$this->set('cod_entidad_bancaria',$cod_entidad_bancaria);
	$this->set('cod_sucursal',$cod_sucursal);
	$this->set('cuenta_bancaria',$cuenta_bancaria);
	$this->set('concepto_manejo_cuenta',$concep_manejo_cuenta[0]['cstd02_cuentas_bancarias']['concepto_manejo']);
	$this->set('titulo_inst', $this->Session->read('entidad_federal'));
	$this->set('titulo_a',$this->Session->read('dependencia'));

	$this->set('ent', $ent[0]['cstd01_entidades_bancarias']['denominacion']);
	$this->set('suc', $suc[0]['cstd01_sucursales_bancarias']['denominacion']);
	$this->set('por_ano', $por_ano);
	$this->set('ano_movimiento', $ano_movimiento);
}




function normalizar_mov_manuales(){
	set_time_limit(0);
	ini_set("memory_limit", "2048M");

	$this->layout = "ajax";

	$sql = "select cod_presi, cod_entidad, cod_entidad, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, tipo_documento, numero_documento, monto, dia_asiento_registro, mes_asiento_registro, ano_asiento_registro from cstd03_movimientos_manuales";
	//$datos=$this->cstd03_movimientos_manuales->execute($sql);

	$si=0;
	$no=1;
	$dato=$this->cstd03_movimientos_manuales->findAll();
	foreach($dato as $datos){
		//$datos['cstd03_movimientos_manuales']['fecha_documento'];
		$cadena=split("-",$datos['cstd03_movimientos_manuales']['fecha_documento']);
   		//return $resultado = $cadena[2].'/'.$cadena[1].'/'.$cadena[0];

   		$cod_presi=$datos['cstd03_movimientos_manuales']['cod_presi'];
   		$cod_entidad=$datos['cstd03_movimientos_manuales']['cod_entidad'];
   		$cod_tipo_inst=$datos['cstd03_movimientos_manuales']['cod_tipo_inst'];
   		$cod_inst=$datos['cstd03_movimientos_manuales']['cod_inst'];
   		$cod_dep=$datos['cstd03_movimientos_manuales']['cod_dep'];
   		$ano_movimiento=$datos['cstd03_movimientos_manuales']['ano_movimiento'];
   		$cod_entidad_bancaria=$datos['cstd03_movimientos_manuales']['cod_entidad_bancaria'];
   		$cod_sucursal=$datos['cstd03_movimientos_manuales']['cod_sucursal'];
   		$cuenta_bancaria=$datos['cstd03_movimientos_manuales']['cuenta_bancaria'];
   		$mes=$cadena[1];//mes del documento
   		$dia=$cadena[2];//dia del documento
		$tipo_documento=$datos['cstd03_movimientos_manuales']['tipo_documento'];
		$numero_documento=$datos['cstd03_movimientos_manuales']['numero_documento'];
		$monto=$datos['cstd03_movimientos_manuales']['monto'];

		$cadena22=split("-",$datos['cstd03_movimientos_manuales']['fecha_proceso_registro']);
		$mes11=$cadena22[1];//mes del documento si, este es el que se esta grabando en movimientos generales, y no deberia
   		$dia11=$cadena22[2];//dia del documento si, este es el que se esta grabando en movimientos generales, y no deberia

		$sql22 = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_movimiento=".$ano_movimiento." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and mes=".$mes11." and dia=".$dia11." and tipo_documento=".$tipo_documento." and numero_documento=".$numero_documento;
		if($this->cstd04_movimientos_generales->findCount($sql22)==0){
			//echo "<br>";
			$sql_insert="insert into cstd04_movimientos_generales values('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst', '$cod_dep', '$ano_movimiento', '$cod_entidad_bancaria', '$cod_sucursal', '$cuenta_bancaria', '$mes', '$dia', '$tipo_documento', '$numero_documento', '$monto');";
			$this->v_cstd_mov_gral->execute($sql_insert);
			$si++;
		}else{
			//echo "No ------- ".$no++;
			$no++;
		}


	}
		echo "<br>Total de registros: ".$si;
		echo "<br>No: ".$no;
}


function normalizar_cstd03_cheque_cuerpo(){
	set_time_limit(0);
	ini_set("memory_limit", "2048M");

	$this->layout = "ajax";


	$si=0;
	$no=1;
	$tipo_documento=4;
	$dato=$this->cstd03_cheque_cuerpo->findAll();
	foreach($dato as $datos){
		//$datos['cstd03_movimientos_manuales']['fecha_documento'];
		$cadena=split("-",$datos['cstd03_cheque_cuerpo']['fecha_cheque']);
   		//return $resultado = $cadena[2].'/'.$cadena[1].'/'.$cadena[0];

   		$cod_presi=$datos['cstd03_cheque_cuerpo']['cod_presi'];
   		$cod_entidad=$datos['cstd03_cheque_cuerpo']['cod_entidad'];
   		$cod_tipo_inst=$datos['cstd03_cheque_cuerpo']['cod_tipo_inst'];
   		$cod_inst=$datos['cstd03_cheque_cuerpo']['cod_inst'];
   		$cod_dep=$datos['cstd03_cheque_cuerpo']['cod_dep'];
   		$ano_movimiento=$datos['cstd03_cheque_cuerpo']['ano_movimiento'];
   		$cod_entidad_bancaria=$datos['cstd03_cheque_cuerpo']['cod_entidad_bancaria'];
   		$cod_sucursal=$datos['cstd03_cheque_cuerpo']['cod_sucursal'];
   		$cuenta_bancaria=$datos['cstd03_cheque_cuerpo']['cuenta_bancaria'];
		$mes=$cadena[1];//mes del cheque
   		$dia=$cadena[2];//dia del cheque
   		$tipo_documento;
		$numero_documento=$datos['cstd03_cheque_cuerpo']['numero_cheque'];
		$monto=$datos['cstd03_cheque_cuerpo']['monto'];

		$cadena22=split("-",$datos['cstd03_cheque_cuerpo']['fecha_proceso_registro']);
		$mes11=$cadena22[1];//mes del documento si, este es el que se esta grabando en movimientos generales, y no deberia
   		$dia11=$cadena22[2];//dia del documento si, este es el que se esta grabando en movimientos generales, y no deberia

		//echo "<br>";
		$sql_insert="insert into cstd04_movimientos_generales values('$cod_presi', '$cod_entidad' ,'$cod_tipo_inst' ,'$cod_inst', '$cod_dep', '$ano_movimiento', '$cod_entidad_bancaria', '$cod_sucursal', '$cuenta_bancaria', '$mes', '$dia', '$tipo_documento', '$numero_documento', '$monto');";
		$this->v_cstd_mov_gral->execute($sql_insert);
		$si++;

	}
		echo "<br>Total de registros: ".$si;
}



//------- Reporte Movimientos Generales generado por fecha -------//
function movimientos_generales_porfecha(){
	$this->layout="ajax";
	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
 	$this->set('vector_cuenta','');

 	$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'direccion_superior');

  //$direccion_superior=  $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
  //$this->concatena($direccion_superior, 'direccion_superior');

	$ano = $this->ano_ejecucion();
    $this->set('ano_movimiento', $ano);
}


function generar_reporte_mov_general_porfecha(){
	$this->layout = "pdf";

	$ano_movimiento = $this->data['cstp04_movimientos_generales']['ano_1'];
	$cod_entidad_bancaria = $this->data['cstp04_movimientos_generales']['cod_entidad_bancaria'];
	$cod_sucursal = $this->data['cstp04_movimientos_generales']['cod_sucursal_bancaria'];
	$cuenta_bancaria =$this->data['cstp04_movimientos_generales']['cuenta_bancaria'];
	$fecha_inicial2 = $this->data['cstp04_movimientos_generales']['fecha_inicial'];// Esta fecha no se modifica en ningun momento
	$fecha_inicial = $this->data['cstp04_movimientos_generales']['fecha_inicial'];// Esta fecha es la que se descompone mas abajo
	$fecha_final = $this->data['cstp04_movimientos_generales']['fecha_final'];

	$cadena_fecha = split("/",$fecha_inicial);
   	$dia=(int) $cadena_fecha[0];
	$mes=(int) $cadena_fecha[1];
   	$ano=(int) $cadena_fecha[2];



   	if($dia==1){
   		if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}
   		switch($mes){
   			case '1':  $dia=31; $mes=12; $ano=$ano-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
   		    case '2':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
   			case '3':  $dia=$dia_feb; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
   			case '4':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
   			case '5':  $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
   			case '6':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
   			case '7':  $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
   			case '8':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
   			case '9':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
   			case '10': $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
   			case '11': $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
   			case '12': $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
   		}
   	}else{
   		$dia=$dia-1;
   		$fecha_inicial=$dia."/".$mes."/".$ano;
   	}

	$sql_suma_anterior = "select Sum(monto) as monto from v_cstd_mov_gral where ".$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and condicion_actividad=1 and (tipo_documento=1 or tipo_documento=2) and (fecha_documento BETWEEN '1/1/2000' and '$fecha_inicial')";
	$sql_resta_anterior = "select Sum(monto) as monto from v_cstd_mov_gral where ".$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and condicion_actividad=1 and (tipo_documento=3 or tipo_documento=4) and (fecha_documento BETWEEN '1/1/2000' and '$fecha_inicial')";
	$suma_anterior=$this->v_cstd_mov_gral->execute($sql_suma_anterior);
	$resta_anterior=$this->v_cstd_mov_gral->execute($sql_resta_anterior);
	$total_anterior = $suma_anterior[0][0]['monto']-$resta_anterior[0][0]['monto'];


	$cond = $this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'";
	$sql_consulta_2 = "select cod_dep, cod_entidad_bancaria as entidad, cod_sucursal as sucursal, cuenta_bancaria, beneficiario, ano_movimiento, dia, mes, tipo_documento, numero_documento, monto, fecha_documento as fecha, condicion_actividad from v_cstd_mov_gral where ".$cond." and (fecha_documento BETWEEN '$fecha_inicial2' AND '$fecha_final')
					   union
					   select cod_dep, cod_entidad_bancaria as entidad, cod_sucursal as sucursal, cuenta_bancaria, beneficiario, ano_movimiento, dia, mes, tipo_documento, numero_documento, monto, fecha_proceso_anulacion as fecha, condicion_actividad from v_cstd_mov_gral where ".$cond." and (fecha_proceso_anulacion BETWEEN '$fecha_inicial2' AND '$fecha_final') order By fecha, tipo_documento, numero_documento;";

	$sql_consulta = "select * from v_cstd_mov_gral where ".$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and ((fecha_documento BETWEEN '$fecha_inicial2' and '$fecha_final') or (fecha_proceso_anulacion BETWEEN '$fecha_inicial2' and '$fecha_final')) order by fecha_documento, fecha_proceso_anulacion, numero_documento";
	$filas_vista=$this->v_cstd_mov_gral->findCount($this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and ((fecha_documento BETWEEN '$fecha_inicial2' and '$fecha_final') or (fecha_proceso_anulacion BETWEEN '$fecha_inicial2' and '$fecha_final'))");
	$vista=$this->v_cstd_mov_gral->execute($sql_consulta_2);

	$filas=0;
	foreach($vista as $v){
		$filas++;
	}

	$ent = $this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad_bancaria);
	$suc = $this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal);
	$concep_manejo_cuenta = $this->cstd02_cuentas_bancarias->findAll($this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'", array('concepto_manejo'));

	$this->set('total_anterior',$total_anterior);
	$this->set('filas_vista',$filas-1);
	$this->set('manuales_generales',$vista);
	$this->set('cod_entidad_bancaria',$cod_entidad_bancaria);
	$this->set('cod_sucursal',$cod_sucursal);
	$this->set('cuenta_bancaria',$cuenta_bancaria);
	$this->set('concepto_manejo_cuenta',$concep_manejo_cuenta[0]['cstd02_cuentas_bancarias']['concepto_manejo']);
	$this->set('titulo_inst', $this->Session->read('entidad_federal'));
	$this->set('titulo_a',$this->Session->read('dependencia'));
	$this->set('ent', $ent[0]['cstd01_entidades_bancarias']['denominacion']);
	$this->set('suc', $suc[0]['cstd01_sucursales_bancarias']['denominacion']);
	$this->set('fecha_inicial2', $fecha_inicial2);
	$this->set('fecha_final', $fecha_final);

	//echo "<br>".$fecha_inicial2;
	//echo "<br>".$fecha_final;
}



/****************************************************************
 * Funciones nuevas del reporte del libro de cuentas bancarias 	*
 * Fecha Creacion:     30-06-08.								*
 * Fecha Modificacion: 30-06-08.								*
 * Funciones:													*
 * 		@ Form_movimiento_general()								*
 * 		@ Generar_reporte_movimiento_general()					*
 ***************************************************************/

 function form_movimiento_general(){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
 	$this->set('vector_cuenta','');

 	$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'direccion_superior');

  //$direccion_superior=  $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
  //$this->concatena($direccion_superior, 'direccion_superior');

	$ano = $this->ano_ejecucion();
    $this->set('operador_1', $this->Session->read('nom_usuario'));
	$this->set('ano_movimiento', $ano);
}


function generar_reporte_movimiento_general(){
	$this->layout = "pdf";

	$ano_movimiento       = $this->data['cstp04_movimientos_generales']['ano_1'];
	$cod_entidad_bancaria = $this->data['cstp04_movimientos_generales']['cod_entidad_bancaria'];
	$cod_sucursal         = $this->data['cstp04_movimientos_generales']['cod_sucursal_bancaria'];
	$cuenta_bancaria      = $this->data['cstp04_movimientos_generales']['cuenta_bancaria'];
	$por_ano              = $this->data['cstp04_movimientos_generales']['por_ano'];
	$condicion_documentos = $this->data['cstp04_movimientos_generales']['condicion_documentos'];//Indica si son todos, activos o anulados.

	$ano                  = $this->ano_ejecucion();
	$ent                  = $this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad_bancaria);
	$suc                  = $this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal);
	$concep_manejo_cuenta = $this->cstd02_cuentas_bancarias->findAll($this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'", array('concepto_manejo'));
	$condicion            = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'";

if($condicion_documentos==1){

	if($por_ano==1){// Reporte generado por mes.
				$selectmes = $this->data['cstp04_movimientos_generales']['selectmes'];
				if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}
				switch($selectmes){
					case '1':  $fecha_inicial = "01/01/".$ano_movimiento; $fecha_final = "31/01/".$ano_movimiento; $ano_anterior=$ano_movimiento-1; $fecha_final_mes_anterior = "31/12/".$ano_anterior; break;
					case '2':  $fecha_inicial = "01/02/".$ano_movimiento; $fecha_final = $dia_feb."/02/".$ano_movimiento; $fecha_final_mes_anterior = "31/01/".$ano_movimiento;   break;
					case '3':  $fecha_inicial = "01/03/".$ano_movimiento; $fecha_final = "31/03/".$ano_movimiento; $fecha_final_mes_anterior = $dia_feb."/02/".$ano_movimiento;   break;
					case '4':  $fecha_inicial = "01/04/".$ano_movimiento; $fecha_final = "30/04/".$ano_movimiento; $fecha_final_mes_anterior = "31/03/".$ano_movimiento;   break;
					case '5':  $fecha_inicial = "01/05/".$ano_movimiento; $fecha_final = "31/05/".$ano_movimiento; $fecha_final_mes_anterior = "30/04/".$ano_movimiento;   break;
					case '6':  $fecha_inicial = "01/06/".$ano_movimiento; $fecha_final = "30/06/".$ano_movimiento; $fecha_final_mes_anterior = "31/05/".$ano_movimiento;   break;
					case '7':  $fecha_inicial = "01/07/".$ano_movimiento; $fecha_final = "31/07/".$ano_movimiento; $fecha_final_mes_anterior = "30/06/".$ano_movimiento;   break;
					case '8':  $fecha_inicial = "01/08/".$ano_movimiento; $fecha_final = "31/08/".$ano_movimiento; $fecha_final_mes_anterior = "31/07/".$ano_movimiento;   break;
					case '9':  $fecha_inicial = "01/09/".$ano_movimiento; $fecha_final = "30/09/".$ano_movimiento; $fecha_final_mes_anterior = "31/08/".$ano_movimiento;   break;
					case '10': $fecha_inicial = "01/10/".$ano_movimiento; $fecha_final = "31/10/".$ano_movimiento; $fecha_final_mes_anterior = "30/09/".$ano_movimiento;   break;
					case '11': $fecha_inicial = "01/11/".$ano_movimiento; $fecha_final = "30/11/".$ano_movimiento; $fecha_final_mes_anterior = "31/10/".$ano_movimiento;   break;
					case '12': $fecha_inicial = "01/12/".$ano_movimiento; $fecha_final = "31/12/".$ano_movimiento; $fecha_final_mes_anterior = "30/11/".$ano_movimiento;   break;
				}
				$sql_suma_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha_documento BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior')";
				$sql_resta_anterior_activos = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha_documento BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior')";
				$sql_suma_anterior_anul     = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha_proceso_anulacion BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior')";
				$sql_resta_anterior_anul    = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha_proceso_anulacion BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior')";
				$sql_vista = "SELECT * FROM v_cstd_mov_gral WHERE ".$condicion." AND ((fecha_documento BETWEEN '$fecha_inicial' AND '$fecha_final') OR (fecha_proceso_anulacion BETWEEN '$fecha_inicial' AND '$fecha_final')) ORDER BY fecha_documento, fecha_proceso_anulacion, tipo_documento, numero_documento";

				$suma_anterior_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
				$resta_anterior_activos = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
				$suma_anterior_anul     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul);
				$resta_anterior_anul    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul);
				$vista                  = $this->v_cstd_mov_gral->execute($sql_vista);

				$suma_anterior_activos[0][0]['monto'];
				$resta_anterior_activos[0][0]['monto'];
				$suma_anterior_anul[0][0]['monto'];
				$resta_anterior_anul[0][0]['monto'];
				$total_anterior = (($suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto']) + $resta_anterior_anul[0][0]['monto']) - $suma_anterior_anul[0][0]['monto'];
				//$total_anterior = $suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto'];


				$this->set('estilo_reporte',$por_ano);//Como se imprime si por mes o por rango de fecha.
				$this->set('selectmes',$selectmes);
				$this->set('ano_movimiento',$ano_movimiento);
				$this->set('total_anterior',$total_anterior);
				$this->set('vista',$vista);
				$mes = array('1'=>'ENERO','2'=>'FEBRERO','3'=>'MARZO','4'=>'ABRIL','5'=>'MAYO','6'=>'JUNIO','7'=>'JULIO','8'=>'AGOSTO','9'=>'SEPTIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
				$this->set('titulo_reporte','MOVIMIENTOS DEL MES DE '.$mes[$selectmes]);

	}elseif($por_ano==2){

				// Reporte generado por rango de fecha.
				$fecha_inicial2 = $this->data['cstp04_movimientos_generales']['fecha_inicial'];// Esta fecha no se modifica en ningun momento
				$fecha_inicial  = $this->data['cstp04_movimientos_generales']['fecha_inicial'];// Esta fecha es la que se descompone mas abajo
				$fecha_final    = $this->data['cstp04_movimientos_generales']['fecha_final'];

				$cadena_fecha = split("/",$fecha_inicial);
			   	$dia=(int) $cadena_fecha[0];
				$mes=(int) $cadena_fecha[1];
			   	$ano=(int) $cadena_fecha[2];

			   	if($dia==1){
			   		if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}
			   		switch($mes){
			   			case '1':  $dia=31; $mes=12; $ano=$ano-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   		    case '2':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '3':  $dia=$dia_feb; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '4':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '5':  $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '6':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '7':  $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '8':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '9':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '10': $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '11': $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '12': $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   		}
			   	}else{
			   		$dia=$dia-1;
			   		$fecha_inicial=$dia."/".$mes."/".$ano;
			   	}
			   	$sql_suma_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha_documento BETWEEN '01/01/2000' AND '$fecha_inicial')";
				$sql_resta_anterior_activos = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha_documento BETWEEN '01/01/2000' AND '$fecha_inicial')";
				$sql_suma_anterior_anul     = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha_proceso_anulacion BETWEEN '01/01/2000' AND '$fecha_inicial')";
				$sql_resta_anterior_anul    = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha_proceso_anulacion BETWEEN '01/01/2000' AND '$fecha_inicial')";
				$sql_vista = "SELECT * FROM v_cstd_mov_gral WHERE ".$condicion." AND ((fecha_documento BETWEEN '$fecha_inicial2' AND '$fecha_final') OR (fecha_proceso_anulacion BETWEEN '$fecha_inicial2' AND '$fecha_final')) ORDER BY fecha_documento, fecha_proceso_anulacion, tipo_documento, numero_documento";

				$suma_anterior_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
				$resta_anterior_activos = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
				$suma_anterior_anul     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul);
				$resta_anterior_anul    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul);
				$vista                  = $this->v_cstd_mov_gral->execute($sql_vista);

				$suma_anterior_activos[0][0]['monto'];
				$resta_anterior_activos[0][0]['monto'];
				$suma_anterior_anul[0][0]['monto'];
				$resta_anterior_anul[0][0]['monto'];
				$total_anterior = (($suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto']) + $resta_anterior_anul[0][0]['monto']) - $suma_anterior_anul[0][0]['monto'];

				$this->set('estilo_reporte',$por_ano);//Como se imprime si por mes o por rango de fecha.
				$this->set('fecha_inicial2',$fecha_inicial2);
				$this->set('fecha_final',$fecha_final);
				$this->set('total_anterior',$total_anterior);
				$this->set('vista',$vista);
				$this->set('titulo_reporte','MOVIMIENTOS DEL '.$fecha_inicial2.' AL '.$fecha_final);
	}
	$this->set('cod_entidad_bancaria',$cod_entidad_bancaria);
	$this->set('cod_sucursal',$cod_sucursal);
	$this->set('cuenta_bancaria',$cuenta_bancaria);
	$this->set('concepto_manejo_cuenta',$concep_manejo_cuenta[0]['cstd02_cuentas_bancarias']['concepto_manejo']);
	$this->set('ent', $ent[0]['cstd01_entidades_bancarias']['denominacion']);
	$this->set('suc', $suc[0]['cstd01_sucursales_bancarias']['denominacion']);

}elseif($condicion_documentos==2){//Solo los documentos activos

		if($por_ano==1){// Reporte generado por mes activos.
				$selectmes = $this->data['cstp04_movimientos_generales']['selectmes'];
				if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}
				switch($selectmes){
					case '1':  $fecha_inicial = "01/01/".$ano_movimiento; $fecha_final = "31/01/".$ano_movimiento; $ano_anterior=$ano_movimiento-1; $fecha_final_mes_anterior = "31/12/".$ano_anterior; break;
					case '2':  $fecha_inicial = "01/02/".$ano_movimiento; $fecha_final = $dia_feb."/02/".$ano_movimiento; $fecha_final_mes_anterior = "31/01/".$ano_movimiento;   break;
					case '3':  $fecha_inicial = "01/03/".$ano_movimiento; $fecha_final = "31/03/".$ano_movimiento; $fecha_final_mes_anterior = $dia_feb."/02/".$ano_movimiento;   break;
					case '4':  $fecha_inicial = "01/04/".$ano_movimiento; $fecha_final = "30/04/".$ano_movimiento; $fecha_final_mes_anterior = "31/03/".$ano_movimiento;   break;
					case '5':  $fecha_inicial = "01/05/".$ano_movimiento; $fecha_final = "31/05/".$ano_movimiento; $fecha_final_mes_anterior = "30/04/".$ano_movimiento;   break;
					case '6':  $fecha_inicial = "01/06/".$ano_movimiento; $fecha_final = "30/06/".$ano_movimiento; $fecha_final_mes_anterior = "31/05/".$ano_movimiento;   break;
					case '7':  $fecha_inicial = "01/07/".$ano_movimiento; $fecha_final = "31/07/".$ano_movimiento; $fecha_final_mes_anterior = "30/06/".$ano_movimiento;   break;
					case '8':  $fecha_inicial = "01/08/".$ano_movimiento; $fecha_final = "31/08/".$ano_movimiento; $fecha_final_mes_anterior = "31/07/".$ano_movimiento;   break;
					case '9':  $fecha_inicial = "01/09/".$ano_movimiento; $fecha_final = "30/09/".$ano_movimiento; $fecha_final_mes_anterior = "31/08/".$ano_movimiento;   break;
					case '10': $fecha_inicial = "01/10/".$ano_movimiento; $fecha_final = "31/10/".$ano_movimiento; $fecha_final_mes_anterior = "30/09/".$ano_movimiento;   break;
					case '11': $fecha_inicial = "01/11/".$ano_movimiento; $fecha_final = "30/11/".$ano_movimiento; $fecha_final_mes_anterior = "31/10/".$ano_movimiento;   break;
					case '12': $fecha_inicial = "01/12/".$ano_movimiento; $fecha_final = "31/12/".$ano_movimiento; $fecha_final_mes_anterior = "30/11/".$ano_movimiento;   break;
				}
				$sql_suma_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha_documento BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior')";
				$sql_resta_anterior_activos = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha_documento BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior')";
				$sql_suma_anterior_anul     = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha_proceso_anulacion BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior')";
				$sql_resta_anterior_anul    = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha_proceso_anulacion BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior')";
				$sql_vista = "SELECT * FROM v_cstd_mov_gral WHERE ".$condicion." AND ((fecha_documento BETWEEN '$fecha_inicial' AND '$fecha_final') OR (fecha_proceso_anulacion BETWEEN '$fecha_inicial' AND '$fecha_final')) ORDER BY fecha_documento, fecha_proceso_anulacion, tipo_documento, numero_documento";

				$suma_anterior_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
				$resta_anterior_activos = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
				$suma_anterior_anul     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul);
				$resta_anterior_anul    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul);
				$vista                  = $this->v_cstd_mov_gral->execute($sql_vista);

				$suma_anterior_activos[0][0]['monto'];
				$resta_anterior_activos[0][0]['monto'];
				$suma_anterior_anul[0][0]['monto'];
				$resta_anterior_anul[0][0]['monto'];
				$total_anterior = (($suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto']) + $resta_anterior_anul[0][0]['monto']) - $suma_anterior_anul[0][0]['monto'];

				$this->set('estilo_reporte',$por_ano);//Como se imprime si por mes o por rango de fecha.
				$this->set('selectmes',$selectmes);
				$this->set('ano_movimiento',$ano_movimiento);
				$this->set('total_anterior',$total_anterior);
				$this->set('vista',$vista);
				$mes = array('1'=>'ENERO','2'=>'FEBRERO','3'=>'MARZO','4'=>'ABRIL','5'=>'MAYO','6'=>'JUNIO','7'=>'JULIO','8'=>'AGOSTO','9'=>'SEPTIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
				$this->set('titulo_reporte','MOVIMIENTOS DEL MES DE '.$mes[$selectmes].'  -  ACTIVOS');

	}elseif($por_ano==2){

				// Reporte generado por mes.
				$fecha_inicial2 = $this->data['cstp04_movimientos_generales']['fecha_inicial'];// Esta fecha no se modifica en ningun momento
				$fecha_inicial  = $this->data['cstp04_movimientos_generales']['fecha_inicial'];// Esta fecha es la que se descompone mas abajo
				$fecha_final    = $this->data['cstp04_movimientos_generales']['fecha_final'];

				$cadena_fecha = split("/",$fecha_inicial);
			   	$dia=(int) $cadena_fecha[0];
				$mes=(int) $cadena_fecha[1];
			   	$ano=(int) $cadena_fecha[2];

			   	if($dia==1){
			   		if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}
			   		switch($mes){
			   			case '1':  $dia=31; $mes=12; $ano=$ano-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   		    case '2':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '3':  $dia=$dia_feb; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '4':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '5':  $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '6':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '7':  $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '8':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '9':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '10': $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '11': $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '12': $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   		}
			   	}else{
			   		$dia=$dia-1;
			   		$fecha_inicial=$dia."/".$mes."/".$ano;
			   	}
			   	$sql_suma_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha_documento BETWEEN '01/01/2000' AND '$fecha_inicial')";
				$sql_resta_anterior_activos = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha_documento BETWEEN '01/01/2000' AND '$fecha_inicial')";
				$sql_suma_anterior_anul     = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha_proceso_anulacion BETWEEN '01/01/2000' AND '$fecha_inicial')";
				$sql_resta_anterior_anul    = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha_proceso_anulacion BETWEEN '01/01/2000' AND '$fecha_inicial')";
				$sql_vista = "SELECT * FROM v_cstd_mov_gral WHERE ".$condicion." AND ((fecha_documento BETWEEN '$fecha_inicial2' AND '$fecha_final') OR (fecha_proceso_anulacion BETWEEN '$fecha_inicial2' AND '$fecha_final')) ORDER BY fecha_documento, fecha_proceso_anulacion, tipo_documento, numero_documento";

				$suma_anterior_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
				$resta_anterior_activos = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
				$suma_anterior_anul     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul);
				$resta_anterior_anul    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul);
				$vista                  = $this->v_cstd_mov_gral->execute($sql_vista);

				$suma_anterior_activos[0][0]['monto'];
				$resta_anterior_activos[0][0]['monto'];
				$suma_anterior_anul[0][0]['monto'];
				$resta_anterior_anul[0][0]['monto'];
				$total_anterior = (($suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto']) + $resta_anterior_anul[0][0]['monto']) - $suma_anterior_anul[0][0]['monto'];


				$this->set('estilo_reporte',$por_ano);//Como se imprime si por mes o por rango de fecha.
				$this->set('fecha_inicial2',$fecha_inicial2);
				$this->set('fecha_final',$fecha_final);
				$this->set('total_anterior',$total_anterior);
				$this->set('vista',$vista);
				$this->set('titulo_reporte','MOVIMIENTOS DEL '.$fecha_inicial2.' AL '.$fecha_final.'  -  ACTIVOS');
	}
	$this->set('cod_entidad_bancaria',$cod_entidad_bancaria);
	$this->set('cod_sucursal',$cod_sucursal);
	$this->set('cuenta_bancaria',$cuenta_bancaria);
	$this->set('concepto_manejo_cuenta',$concep_manejo_cuenta[0]['cstd02_cuentas_bancarias']['concepto_manejo']);
	$this->set('ent', $ent[0]['cstd01_entidades_bancarias']['denominacion']);
	$this->set('suc', $suc[0]['cstd01_sucursales_bancarias']['denominacion']);

	$this->reporte_movimiento_general_activos();
	$this->render("reporte_movimiento_general_activos");

}elseif($condicion_documentos==3){//Solo los documentos anulados

		if($por_ano==1){// Reporte generado por mes activos.
				$selectmes = $this->data['cstp04_movimientos_generales']['selectmes'];
				if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}
				switch($selectmes){
					case '1':  $fecha_inicial = "01/01/".$ano_movimiento; $fecha_final = "31/01/".$ano_movimiento; $ano_anterior=$ano_movimiento-1; $fecha_final_mes_anterior = "31/12/".$ano_anterior; break;
					case '2':  $fecha_inicial = "01/02/".$ano_movimiento; $fecha_final = $dia_feb."/02/".$ano_movimiento; $fecha_final_mes_anterior = "31/01/".$ano_movimiento;   break;
					case '3':  $fecha_inicial = "01/03/".$ano_movimiento; $fecha_final = "31/03/".$ano_movimiento; $fecha_final_mes_anterior = $dia_feb."/02/".$ano_movimiento;   break;
					case '4':  $fecha_inicial = "01/04/".$ano_movimiento; $fecha_final = "30/04/".$ano_movimiento; $fecha_final_mes_anterior = "31/03/".$ano_movimiento;   break;
					case '5':  $fecha_inicial = "01/05/".$ano_movimiento; $fecha_final = "31/05/".$ano_movimiento; $fecha_final_mes_anterior = "30/04/".$ano_movimiento;   break;
					case '6':  $fecha_inicial = "01/06/".$ano_movimiento; $fecha_final = "30/06/".$ano_movimiento; $fecha_final_mes_anterior = "31/05/".$ano_movimiento;   break;
					case '7':  $fecha_inicial = "01/07/".$ano_movimiento; $fecha_final = "31/07/".$ano_movimiento; $fecha_final_mes_anterior = "30/06/".$ano_movimiento;   break;
					case '8':  $fecha_inicial = "01/08/".$ano_movimiento; $fecha_final = "31/08/".$ano_movimiento; $fecha_final_mes_anterior = "31/07/".$ano_movimiento;   break;
					case '9':  $fecha_inicial = "01/09/".$ano_movimiento; $fecha_final = "30/09/".$ano_movimiento; $fecha_final_mes_anterior = "31/08/".$ano_movimiento;   break;
					case '10': $fecha_inicial = "01/10/".$ano_movimiento; $fecha_final = "31/10/".$ano_movimiento; $fecha_final_mes_anterior = "30/09/".$ano_movimiento;   break;
					case '11': $fecha_inicial = "01/11/".$ano_movimiento; $fecha_final = "30/11/".$ano_movimiento; $fecha_final_mes_anterior = "31/10/".$ano_movimiento;   break;
					case '12': $fecha_inicial = "01/12/".$ano_movimiento; $fecha_final = "31/12/".$ano_movimiento; $fecha_final_mes_anterior = "30/11/".$ano_movimiento;   break;
				}
				$sql_suma_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha_documento BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior')";
				$sql_resta_anterior_activos = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha_documento BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior')";
				$sql_suma_anterior_anul     = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha_proceso_anulacion BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior')";
				$sql_resta_anterior_anul    = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha_proceso_anulacion BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior')";
				$sql_vista = "SELECT * FROM v_cstd_mov_gral WHERE ".$condicion." AND ((fecha_documento BETWEEN '$fecha_inicial' AND '$fecha_final') OR (fecha_proceso_anulacion BETWEEN '$fecha_inicial' AND '$fecha_final')) ORDER BY fecha_documento, fecha_proceso_anulacion, tipo_documento, numero_documento";

				$suma_anterior_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
				$resta_anterior_activos = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
				$suma_anterior_anul     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul);
				$resta_anterior_anul    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul);
				$vista                  = $this->v_cstd_mov_gral->execute($sql_vista);

				$suma_anterior_activos[0][0]['monto'];
				$resta_anterior_activos[0][0]['monto'];
				$suma_anterior_anul[0][0]['monto'];
				$resta_anterior_anul[0][0]['monto'];
				$total_anterior = (($suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto']) + $resta_anterior_anul[0][0]['monto']) - $suma_anterior_anul[0][0]['monto'];

				$this->set('estilo_reporte',$por_ano);//Como se imprime si por mes o por rango de fecha.
				$this->set('selectmes',$selectmes);
				$this->set('ano_movimiento',$ano_movimiento);
				$this->set('total_anterior',$total_anterior);
				$this->set('vista',$vista);
				$mes = array('1'=>'ENERO','2'=>'FEBRERO','3'=>'MARZO','4'=>'ABRIL','5'=>'MAYO','6'=>'JUNIO','7'=>'JULIO','8'=>'AGOSTO','9'=>'SEPTIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
				$this->set('titulo_reporte','MOVIMIENTOS DEL MES DE '.$mes[$selectmes].'  -  ANULADOS');

	}elseif($por_ano==2){

				// Reporte generado por mes.
				$fecha_inicial2 = $this->data['cstp04_movimientos_generales']['fecha_inicial'];// Esta fecha no se modifica en ningun momento
				$fecha_inicial  = $this->data['cstp04_movimientos_generales']['fecha_inicial'];// Esta fecha es la que se descompone mas abajo
				$fecha_final    = $this->data['cstp04_movimientos_generales']['fecha_final'];

				$cadena_fecha = split("/",$fecha_inicial);
			   	$dia=(int) $cadena_fecha[0];
				$mes=(int) $cadena_fecha[1];
			   	$ano=(int) $cadena_fecha[2];

			   	if($dia==1){
			   		if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}
			   		switch($mes){
			   			case '1':  $dia=31; $mes=12; $ano=$ano-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   		    case '2':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '3':  $dia=$dia_feb; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '4':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '5':  $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '6':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '7':  $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '8':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '9':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '10': $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '11': $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '12': $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   		}
			   	}else{
			   		$dia=$dia-1;
			   		$fecha_inicial=$dia."/".$mes."/".$ano;
			   	}
			   	$sql_suma_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha_documento BETWEEN '01/01/2000' AND '$fecha_inicial')";
				$sql_resta_anterior_activos = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha_documento BETWEEN '01/01/2000' AND '$fecha_inicial')";
				$sql_suma_anterior_anul     = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha_proceso_anulacion BETWEEN '01/01/2000' AND '$fecha_inicial')";
				$sql_resta_anterior_anul    = "SELECT Sum(monto) as monto FROM v_cstd_mov_gral WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha_proceso_anulacion BETWEEN '01/01/2000' AND '$fecha_inicial')";
				$sql_vista = "SELECT * FROM v_cstd_mov_gral WHERE ".$condicion." AND ((fecha_documento BETWEEN '$fecha_inicial2' AND '$fecha_final') OR (fecha_proceso_anulacion BETWEEN '$fecha_inicial2' AND '$fecha_final')) ORDER BY fecha_documento, fecha_proceso_anulacion, tipo_documento, numero_documento";

				$suma_anterior_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
				$resta_anterior_activos = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
				$suma_anterior_anul     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul);
				$resta_anterior_anul    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul);
				$vista                  = $this->v_cstd_mov_gral->execute($sql_vista);

				$suma_anterior_activos[0][0]['monto'];
				$resta_anterior_activos[0][0]['monto'];
				$suma_anterior_anul[0][0]['monto'];
				$resta_anterior_anul[0][0]['monto'];
				$total_anterior = (($suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto']) + $resta_anterior_anul[0][0]['monto']) - $suma_anterior_anul[0][0]['monto'];

				$this->set('estilo_reporte',$por_ano);//Como se imprime si por mes o por rango de fecha.
				$this->set('fecha_inicial2',$fecha_inicial2);
				$this->set('fecha_final',$fecha_final);
				$this->set('total_anterior',$total_anterior);
				$this->set('vista',$vista);
				$this->set('titulo_reporte','MOVIMIENTOS DEL '.$fecha_inicial2.' AL '.$fecha_final.'  -  ANULADOS');
	}
	$this->set('cod_entidad_bancaria',$cod_entidad_bancaria);
	$this->set('cod_sucursal',$cod_sucursal);
	$this->set('cuenta_bancaria',$cuenta_bancaria);
	$this->set('concepto_manejo_cuenta',$concep_manejo_cuenta[0]['cstd02_cuentas_bancarias']['concepto_manejo']);
	$this->set('ent', $ent[0]['cstd01_entidades_bancarias']['denominacion']);
	$this->set('suc', $suc[0]['cstd01_sucursales_bancarias']['denominacion']);

	$this->reporte_movimiento_general_anulados();
	$this->render("reporte_movimiento_general_anulados");
}

}//generar_reporte_movimiento_general



function reporte_movimiento_general_activos(){
	$this->layout="pdf";
	//echo "vista activos";
}


function reporte_movimiento_general_anulados(){
	$this->layout="pdf";
	//echo "vista anulados";
}


function anio_bisiesto($anyo){
	if(!checkdate(02,29,$anyo)){
		return false;
	}else{
		return true;
	}
}


function form_movimiento_general_2(){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
 	$this->set('vector_cuenta','');
    $entidades=$this->cstd01_entidades_bancarias->findAll(null, null, 'cod_entidad_bancaria ASC');
    $direccion_superior=array();
    $codEntidad  = array();
    $denoEntidad = array();
    $total_entidades = count($entidades);
	if($total_entidades==0){
		$direccion_superior = array();
	}else{
		for($i=0; $i<$total_entidades; $i++){
			$codEntidad[]  = mascara($entidades[$i]['cstd01_entidades_bancarias']['cod_entidad_bancaria'], 4);
			$denoEntidad[] = mascara($entidades[$i]['cstd01_entidades_bancarias']['cod_entidad_bancaria'], 4)." - ".$entidades[$i]['cstd01_entidades_bancarias']['denominacion'];
		}
		$direccion_superior = array_combine($codEntidad, $denoEntidad);
	}
	$ano = $this->ano_ejecucion();
	$this->set('direccion_superior',$direccion_superior);
    $this->set('operador_1', $this->Session->read('nom_usuario'));
	$this->set('ano_movimiento', $ano);

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='705'");

	if($cont==0){
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','');
		$this->set('cargo_tercera_firma','');
		$this->set('primera_copia','');
		$this->set('segunda_copia','');
		$this->set('tercera_copia','');
		$this->set('tipo_doc_anul',705);
		$this->set('firma',1);

	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=705");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('primera_copia',$firmantes[0]['cugd07_firmas_oficio_anulacion']['primera_copia']);
		$this->set('segunda_copia',$firmantes[0]['cugd07_firmas_oficio_anulacion']['segunda_copia']);
		$this->set('tercera_copia',$firmantes[0]['cugd07_firmas_oficio_anulacion']['tercera_copia']);
		$this->set('tipo_doc_anul',705);
		$this->set('firma',2);
	}
}


function generar_reporte_movimiento_general_2(){
	$this->layout = "pdf";

	$ano_movimiento       = $this->data['cstp04_movimientos_generales']['ano_1'];
	$cod_entidad_bancaria = $this->data['cstp04_movimientos_generales']['cod_entidad_bancaria'];
	$cod_sucursal         = $this->data['cstp04_movimientos_generales']['cod_sucursal_bancaria'];
	$cuenta_bancaria      = $this->data['cstp04_movimientos_generales']['cuenta_bancaria'];
	$por_ano              = $this->data['cstp04_movimientos_generales']['por_ano'];
	$condicion_documentos = $this->data['cstp04_movimientos_generales']['condicion_documentos'];//Indica si son todos, activos o anulados.

	$ano                  = $this->ano_ejecucion();
	$ent                  = $this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad_bancaria);
	$suc                  = $this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal);
	$concep_manejo_cuenta = $this->cstd02_cuentas_bancarias->findAll($this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'", array('concepto_manejo'));
	// Esta condicion se comento porque se le quito el ano de movimiento, ya que se esta filtrando o tomando las fechas de los documentos.
	$condicion            = $this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'";
	//$condicion         = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'";
	$condicion_sin_ano    = $this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'";

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='705'");

	if($cont==0){
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','');
		$this->set('cargo_tercera_firma','');
		$this->set('primera_copia','');
		$this->set('segunda_copia','');
		$this->set('tercera_copia','');

	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=705");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('primera_copia',$firmantes[0]['cugd07_firmas_oficio_anulacion']['primera_copia']);
		$this->set('segunda_copia',$firmantes[0]['cugd07_firmas_oficio_anulacion']['segunda_copia']);
		$this->set('tercera_copia',$firmantes[0]['cugd07_firmas_oficio_anulacion']['tercera_copia']);
	}

if($condicion_documentos==1){

	if($por_ano==1){// Reporte generado por mes.
				$selectmes = $this->data['cstp04_movimientos_generales']['selectmes'];
				if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}
				switch($selectmes){
					case '1':  $fecha_inicial = "01/01/".$ano_movimiento; $fecha_final = "31/01/".$ano_movimiento; $ano_anterior=$ano_movimiento-1; $fecha_final_mes_anterior = "31/12/".$ano_anterior; break;
					case '2':  $fecha_inicial = "01/02/".$ano_movimiento; $fecha_final = $dia_feb."/02/".$ano_movimiento; $fecha_final_mes_anterior = "31/01/".$ano_movimiento;   break;
					case '3':  $fecha_inicial = "01/03/".$ano_movimiento; $fecha_final = "31/03/".$ano_movimiento; $fecha_final_mes_anterior = $dia_feb."/02/".$ano_movimiento;   break;
					case '4':  $fecha_inicial = "01/04/".$ano_movimiento; $fecha_final = "30/04/".$ano_movimiento; $fecha_final_mes_anterior = "31/03/".$ano_movimiento;   break;
					case '5':  $fecha_inicial = "01/05/".$ano_movimiento; $fecha_final = "31/05/".$ano_movimiento; $fecha_final_mes_anterior = "30/04/".$ano_movimiento;   break;
					case '6':  $fecha_inicial = "01/06/".$ano_movimiento; $fecha_final = "30/06/".$ano_movimiento; $fecha_final_mes_anterior = "31/05/".$ano_movimiento;   break;
					case '7':  $fecha_inicial = "01/07/".$ano_movimiento; $fecha_final = "31/07/".$ano_movimiento; $fecha_final_mes_anterior = "30/06/".$ano_movimiento;   break;
					case '8':  $fecha_inicial = "01/08/".$ano_movimiento; $fecha_final = "31/08/".$ano_movimiento; $fecha_final_mes_anterior = "31/07/".$ano_movimiento;   break;
					case '9':  $fecha_inicial = "01/09/".$ano_movimiento; $fecha_final = "30/09/".$ano_movimiento; $fecha_final_mes_anterior = "31/08/".$ano_movimiento;   break;
					case '10': $fecha_inicial = "01/10/".$ano_movimiento; $fecha_final = "31/10/".$ano_movimiento; $fecha_final_mes_anterior = "30/09/".$ano_movimiento;   break;
					case '11': $fecha_inicial = "01/11/".$ano_movimiento; $fecha_final = "30/11/".$ano_movimiento; $fecha_final_mes_anterior = "31/10/".$ano_movimiento;   break;
					case '12': $fecha_inicial = "01/12/".$ano_movimiento; $fecha_final = "31/12/".$ano_movimiento; $fecha_final_mes_anterior = "30/11/".$ano_movimiento;   break;
				}
				$sql_suma_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior') AND tipo=1";
				$sql_resta_anterior_activos = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior') AND tipo=1";
				$sql_suma_anterior_anul     = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior') AND tipo=2";
				$sql_resta_anterior_anul    = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior') AND tipo=2";
				$sql_vista = "SELECT * FROM v_cstd_mov_general WHERE ".$condicion." AND (fecha BETWEEN '$fecha_inicial' AND '$fecha_final') ORDER BY fecha, tipo_documento, numero_documento, condicion_actividad, tipo";
				$suma_anterior_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
				$resta_anterior_activos = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
				$suma_anterior_anul     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul);
				$resta_anterior_anul    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul);
				$vista                  = $this->v_cstd_mov_gral->execute($sql_vista);
				$suma_anterior_activos[0][0]['monto'];
				$resta_anterior_activos[0][0]['monto'];
				$suma_anterior_anul[0][0]['monto'];
				$resta_anterior_anul[0][0]['monto'];
				$total_anterior = (($suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto']) + $resta_anterior_anul[0][0]['monto']) - $suma_anterior_anul[0][0]['monto'];

				$this->set('estilo_reporte',$por_ano);//Como se imprime si por mes o por rango de fecha.
				$this->set('selectmes',$selectmes);
				$this->set('ano_movimiento',$ano_movimiento);
				$this->set('total_anterior',$total_anterior);
				$this->set('vista',$vista);
				$this->set('fecha_inicial2',$fecha_inicial);
				$this->set('fecha_final',$fecha_final);
				$mes = array('1'=>'ENERO','2'=>'FEBRERO','3'=>'MARZO','4'=>'ABRIL','5'=>'MAYO','6'=>'JUNIO','7'=>'JULIO','8'=>'AGOSTO','9'=>'SEPTIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
				$this->set('titulo_reporte','MOVIMIENTOS DEL MES DE '.$mes[$selectmes]);

	}elseif($por_ano==2){

				// Reporte generado por rango de fecha.
				$fecha_inicial2 = $this->data['cstp04_movimientos_generales']['fecha_inicial'];// Esta fecha no se modifica en ningun momento
				$fecha_inicial  = $this->data['cstp04_movimientos_generales']['fecha_inicial'];// Esta fecha es la que se descompone mas abajo
				$fecha_final    = $this->data['cstp04_movimientos_generales']['fecha_final'];

				$cadena_fecha = split("/",$fecha_inicial);
			   	$dia=(int) $cadena_fecha[0];
				$mes=(int) $cadena_fecha[1];
			   	$ano=(int) $cadena_fecha[2];

			   	if($dia==1){
			   		if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}
			   		switch($mes){
			   			case '1':  $dia=31; $mes=12; $ano=$ano-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   		    case '2':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '3':  $dia=$dia_feb; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '4':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '5':  $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '6':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '7':  $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '8':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '9':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '10': $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '11': $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '12': $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   		}
			   	}else{
			   		$dia=$dia-1;
			   		$fecha_inicial=$dia."/".$mes."/".$ano;
			   	}

				$sql_suma_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_inicial' AND tipo=1)";
				$sql_resta_anterior_activos = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_inicial' AND tipo=1)";
				$sql_suma_anterior_anul     = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_inicial' AND tipo=2)";
				$sql_resta_anterior_anul    = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_inicial' AND tipo=2)";
				$sql_vista = "SELECT * FROM v_cstd_mov_general WHERE ".$condicion." AND (fecha BETWEEN '$fecha_inicial2' AND '$fecha_final') ORDER BY fecha, tipo_documento, numero_documento, condicion_actividad, tipo";

				$suma_anterior_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
				$resta_anterior_activos = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
				$suma_anterior_anul     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul);
				$resta_anterior_anul    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul);
				$vista                  = $this->v_cstd_mov_gral->execute($sql_vista);

				$suma_anterior_activos[0][0]['monto'];
				$resta_anterior_activos[0][0]['monto'];
				$suma_anterior_anul[0][0]['monto'];
				$resta_anterior_anul[0][0]['monto'];
				$total_anterior = (($suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto']) + $resta_anterior_anul[0][0]['monto']) - $suma_anterior_anul[0][0]['monto'];

				$this->set('estilo_reporte',$por_ano);//Como se imprime si por mes o por rango de fecha.
				$this->set('fecha_inicial2',$fecha_inicial2);
				$this->set('fecha_final',$fecha_final);
				$this->set('total_anterior',$total_anterior);
				$this->set('vista',$vista);
				$this->set('titulo_reporte','MOVIMIENTOS DEL '.$fecha_inicial2.' AL '.$fecha_final);
	}
	$this->set('cod_entidad_bancaria',$cod_entidad_bancaria);
	$this->set('cod_sucursal',$cod_sucursal);
	$this->set('cuenta_bancaria',$cuenta_bancaria);
	$this->set('concepto_manejo_cuenta',$concep_manejo_cuenta[0]['cstd02_cuentas_bancarias']['concepto_manejo']);
	$this->set('ent', $ent[0]['cstd01_entidades_bancarias']['denominacion']);
	$this->set('suc', $suc[0]['cstd01_sucursales_bancarias']['denominacion']);

}elseif($condicion_documentos==2){//Solo los documentos activos

		if($por_ano==1){// Reporte generado por mes activos.
				$selectmes = $this->data['cstp04_movimientos_generales']['selectmes'];
				if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}
				switch($selectmes){
					case '1':  $fecha_inicial = "01/01/".$ano_movimiento; $fecha_final = "31/01/".$ano_movimiento; $ano_anterior=$ano_movimiento-1; $fecha_final_mes_anterior = "31/12/".$ano_anterior; break;
					case '2':  $fecha_inicial = "01/02/".$ano_movimiento; $fecha_final = $dia_feb."/02/".$ano_movimiento; $fecha_final_mes_anterior = "31/01/".$ano_movimiento;   break;
					case '3':  $fecha_inicial = "01/03/".$ano_movimiento; $fecha_final = "31/03/".$ano_movimiento; $fecha_final_mes_anterior = $dia_feb."/02/".$ano_movimiento;   break;
					case '4':  $fecha_inicial = "01/04/".$ano_movimiento; $fecha_final = "30/04/".$ano_movimiento; $fecha_final_mes_anterior = "31/03/".$ano_movimiento;   break;
					case '5':  $fecha_inicial = "01/05/".$ano_movimiento; $fecha_final = "31/05/".$ano_movimiento; $fecha_final_mes_anterior = "30/04/".$ano_movimiento;   break;
					case '6':  $fecha_inicial = "01/06/".$ano_movimiento; $fecha_final = "30/06/".$ano_movimiento; $fecha_final_mes_anterior = "31/05/".$ano_movimiento;   break;
					case '7':  $fecha_inicial = "01/07/".$ano_movimiento; $fecha_final = "31/07/".$ano_movimiento; $fecha_final_mes_anterior = "30/06/".$ano_movimiento;   break;
					case '8':  $fecha_inicial = "01/08/".$ano_movimiento; $fecha_final = "31/08/".$ano_movimiento; $fecha_final_mes_anterior = "31/07/".$ano_movimiento;   break;
					case '9':  $fecha_inicial = "01/09/".$ano_movimiento; $fecha_final = "30/09/".$ano_movimiento; $fecha_final_mes_anterior = "31/08/".$ano_movimiento;   break;
					case '10': $fecha_inicial = "01/10/".$ano_movimiento; $fecha_final = "31/10/".$ano_movimiento; $fecha_final_mes_anterior = "30/09/".$ano_movimiento;   break;
					case '11': $fecha_inicial = "01/11/".$ano_movimiento; $fecha_final = "30/11/".$ano_movimiento; $fecha_final_mes_anterior = "31/10/".$ano_movimiento;   break;
					case '12': $fecha_inicial = "01/12/".$ano_movimiento; $fecha_final = "31/12/".$ano_movimiento; $fecha_final_mes_anterior = "30/11/".$ano_movimiento;   break;
				}
				$sql_suma_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior') AND tipo=1";
				$sql_resta_anterior_activos = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior') AND tipo=1";
				$sql_suma_anterior_anul     = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior') AND tipo=2";
				$sql_resta_anterior_anul    = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior') AND tipo=2";
				$sql_vista = "SELECT * FROM v_cstd_mov_general WHERE ".$condicion." AND (fecha BETWEEN '$fecha_inicial' AND '$fecha_final') ORDER BY fecha, tipo_documento, numero_documento, condicion_actividad, tipo";
				$suma_anterior_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
				$resta_anterior_activos = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
				$suma_anterior_anul     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul);
				$resta_anterior_anul    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul);
				$vista                  = $this->v_cstd_mov_gral->execute($sql_vista);
				$suma_anterior_activos[0][0]['monto'];
				$resta_anterior_activos[0][0]['monto'];
				$suma_anterior_anul[0][0]['monto'];
				$resta_anterior_anul[0][0]['monto'];
				$total_anterior = (($suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto']) + $resta_anterior_anul[0][0]['monto']) - $suma_anterior_anul[0][0]['monto'];

				$this->set('estilo_reporte',$por_ano);//Como se imprime si por mes o por rango de fecha.
				$this->set('selectmes',$selectmes);
				$this->set('ano_movimiento',$ano_movimiento);
				$this->set('total_anterior',$total_anterior);
				$this->set('vista',$vista);
				$this->set('fecha_inicial2',$fecha_inicial);
				$this->set('fecha_final',$fecha_final);
				$mes = array('1'=>'ENERO','2'=>'FEBRERO','3'=>'MARZO','4'=>'ABRIL','5'=>'MAYO','6'=>'JUNIO','7'=>'JULIO','8'=>'AGOSTO','9'=>'SEPTIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
				$this->set('titulo_reporte','MOVIMIENTOS DEL MES DE '.$mes[$selectmes].'  -  ACTIVOS');

	}elseif($por_ano==2){

				// Reporte generado por mes.
				$fecha_inicial2 = $this->data['cstp04_movimientos_generales']['fecha_inicial'];// Esta fecha no se modifica en ningun momento
				$fecha_inicial  = $this->data['cstp04_movimientos_generales']['fecha_inicial'];// Esta fecha es la que se descompone mas abajo
				$fecha_final    = $this->data['cstp04_movimientos_generales']['fecha_final'];

				$cadena_fecha = split("/",$fecha_inicial);
			   	$dia=(int) $cadena_fecha[0];
				$mes=(int) $cadena_fecha[1];
			   	$ano=(int) $cadena_fecha[2];

			   	if($dia==1){
			   		if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}
			   		switch($mes){
			   			case '1':  $dia=31; $mes=12; $ano=$ano-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   		    case '2':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '3':  $dia=$dia_feb; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '4':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '5':  $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '6':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '7':  $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '8':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '9':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '10': $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '11': $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '12': $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   		}
			   	}else{
			   		$dia=$dia-1;
			   		$fecha_inicial=$dia."/".$mes."/".$ano;
			   	}
			   	$sql_suma_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_inicial' AND tipo=1)";
				$sql_resta_anterior_activos = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_inicial' AND tipo=1)";
				$sql_suma_anterior_anul     = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_inicial' AND tipo=2)";
				$sql_resta_anterior_anul    = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_inicial' AND tipo=2)";
				$sql_vista = "SELECT * FROM v_cstd_mov_general WHERE ".$condicion." AND (fecha BETWEEN '$fecha_inicial2' AND '$fecha_final') ORDER BY fecha, tipo_documento, numero_documento, condicion_actividad, tipo";

				$suma_anterior_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
				$resta_anterior_activos = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
				$suma_anterior_anul     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul);
				$resta_anterior_anul    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul);
				$vista                  = $this->v_cstd_mov_gral->execute($sql_vista);

				$suma_anterior_activos[0][0]['monto'];
				$resta_anterior_activos[0][0]['monto'];
				$suma_anterior_anul[0][0]['monto'];
				$resta_anterior_anul[0][0]['monto'];
				$total_anterior = (($suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto']) + $resta_anterior_anul[0][0]['monto']) - $suma_anterior_anul[0][0]['monto'];


				$this->set('estilo_reporte',$por_ano);//Como se imprime si por mes o por rango de fecha.
				$this->set('fecha_inicial2',$fecha_inicial2);
				$this->set('fecha_final',$fecha_final);
				$this->set('total_anterior',$total_anterior);
				$this->set('vista',$vista);
				$this->set('titulo_reporte','MOVIMIENTOS DEL '.$fecha_inicial2.' AL '.$fecha_final.'  -  ACTIVOS');
	}
	$this->set('cod_entidad_bancaria',$cod_entidad_bancaria);
	$this->set('cod_sucursal',$cod_sucursal);
	$this->set('cuenta_bancaria',$cuenta_bancaria);
	$this->set('concepto_manejo_cuenta',$concep_manejo_cuenta[0]['cstd02_cuentas_bancarias']['concepto_manejo']);
	$this->set('ent', $ent[0]['cstd01_entidades_bancarias']['denominacion']);
	$this->set('suc', $suc[0]['cstd01_sucursales_bancarias']['denominacion']);

	$this->reporte_movimiento_general_activos_2();
	$this->render("reporte_movimiento_general_activos_2");

}elseif($condicion_documentos==3){//Solo los documentos anulados

		if($por_ano==1){// Reporte generado por mes activos.
				$selectmes = $this->data['cstp04_movimientos_generales']['selectmes'];
				if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}
				switch($selectmes){
					case '1':  $fecha_inicial = "01/01/".$ano_movimiento; $fecha_final = "31/01/".$ano_movimiento; $ano_anterior=$ano_movimiento-1; $fecha_final_mes_anterior = "31/12/".$ano_anterior; break;
					case '2':  $fecha_inicial = "01/02/".$ano_movimiento; $fecha_final = $dia_feb."/02/".$ano_movimiento; $fecha_final_mes_anterior = "31/01/".$ano_movimiento;   break;
					case '3':  $fecha_inicial = "01/03/".$ano_movimiento; $fecha_final = "31/03/".$ano_movimiento; $fecha_final_mes_anterior = $dia_feb."/02/".$ano_movimiento;   break;
					case '4':  $fecha_inicial = "01/04/".$ano_movimiento; $fecha_final = "30/04/".$ano_movimiento; $fecha_final_mes_anterior = "31/03/".$ano_movimiento;   break;
					case '5':  $fecha_inicial = "01/05/".$ano_movimiento; $fecha_final = "31/05/".$ano_movimiento; $fecha_final_mes_anterior = "30/04/".$ano_movimiento;   break;
					case '6':  $fecha_inicial = "01/06/".$ano_movimiento; $fecha_final = "30/06/".$ano_movimiento; $fecha_final_mes_anterior = "31/05/".$ano_movimiento;   break;
					case '7':  $fecha_inicial = "01/07/".$ano_movimiento; $fecha_final = "31/07/".$ano_movimiento; $fecha_final_mes_anterior = "30/06/".$ano_movimiento;   break;
					case '8':  $fecha_inicial = "01/08/".$ano_movimiento; $fecha_final = "31/08/".$ano_movimiento; $fecha_final_mes_anterior = "31/07/".$ano_movimiento;   break;
					case '9':  $fecha_inicial = "01/09/".$ano_movimiento; $fecha_final = "30/09/".$ano_movimiento; $fecha_final_mes_anterior = "31/08/".$ano_movimiento;   break;
					case '10': $fecha_inicial = "01/10/".$ano_movimiento; $fecha_final = "31/10/".$ano_movimiento; $fecha_final_mes_anterior = "30/09/".$ano_movimiento;   break;
					case '11': $fecha_inicial = "01/11/".$ano_movimiento; $fecha_final = "30/11/".$ano_movimiento; $fecha_final_mes_anterior = "31/10/".$ano_movimiento;   break;
					case '12': $fecha_inicial = "01/12/".$ano_movimiento; $fecha_final = "31/12/".$ano_movimiento; $fecha_final_mes_anterior = "30/11/".$ano_movimiento;   break;
				}
				$sql_suma_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior') AND tipo=1";
				$sql_resta_anterior_activos = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior') AND tipo=1";
				$sql_suma_anterior_anul     = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior') AND tipo=2";
				$sql_resta_anterior_anul    = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_final_mes_anterior') AND tipo=2";
				$sql_vista = "SELECT * FROM v_cstd_mov_general WHERE ".$condicion." AND (fecha BETWEEN '$fecha_inicial' AND '$fecha_final') ORDER BY fecha, tipo_documento, numero_documento, condicion_actividad, tipo";
				$suma_anterior_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
				$resta_anterior_activos = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
				$suma_anterior_anul     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul);
				$resta_anterior_anul    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul);
				$vista                  = $this->v_cstd_mov_gral->execute($sql_vista);
				$suma_anterior_activos[0][0]['monto'];
				$resta_anterior_activos[0][0]['monto'];
				$suma_anterior_anul[0][0]['monto'];
				$resta_anterior_anul[0][0]['monto'];
				$total_anterior = (($suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto']) + $resta_anterior_anul[0][0]['monto']) - $suma_anterior_anul[0][0]['monto'];

				$this->set('estilo_reporte',$por_ano);//Como se imprime si por mes o por rango de fecha.
				$this->set('selectmes',$selectmes);
				$this->set('ano_movimiento',$ano_movimiento);
				$this->set('total_anterior',$total_anterior);
				$this->set('vista',$vista);
				$this->set('fecha_inicial2',$fecha_inicial);
				$this->set('fecha_final',$fecha_final);
				$mes = array('1'=>'ENERO','2'=>'FEBRERO','3'=>'MARZO','4'=>'ABRIL','5'=>'MAYO','6'=>'JUNIO','7'=>'JULIO','8'=>'AGOSTO','9'=>'SEPTIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
				$this->set('titulo_reporte','MOVIMIENTOS DEL MES DE '.$mes[$selectmes].'  -  ANULADOS');

	}elseif($por_ano==2){

				// Reporte generado por rango de fecha.
				$fecha_inicial2 = $this->data['cstp04_movimientos_generales']['fecha_inicial'];// Esta fecha no se modifica en ningun momento
				$fecha_inicial  = $this->data['cstp04_movimientos_generales']['fecha_inicial'];// Esta fecha es la que se descompone mas abajo
				$fecha_final    = $this->data['cstp04_movimientos_generales']['fecha_final'];

				$cadena_fecha = split("/",$fecha_inicial);
			   	$dia=(int) $cadena_fecha[0];
				$mes=(int) $cadena_fecha[1];
			   	$ano=(int) $cadena_fecha[2];

			   	if($dia==1){
			   		if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}
			   		switch($mes){
			   			case '1':  $dia=31; $mes=12; $ano=$ano-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   		    case '2':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '3':  $dia=$dia_feb; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '4':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '5':  $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '6':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '7':  $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '8':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '9':  $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '10': $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '11': $dia=31; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   			case '12': $dia=30; $mes=$mes-1; $fecha_inicial=$dia."/".$mes."/".$ano; break;
			   		}
			   	}else{
			   		$dia=$dia-1;
			   		$fecha_inicial=$dia."/".$mes."/".$ano;
			   	}
				$sql_suma_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_inicial' AND tipo=1)";
				$sql_resta_anterior_activos = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_inicial' AND tipo=1)";
				$sql_suma_anterior_anul     = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_inicial' AND tipo=2)";
				$sql_resta_anterior_anul    = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_inicial' AND tipo=2)";
				$sql_vista = "SELECT * FROM v_cstd_mov_general WHERE ".$condicion." AND (fecha BETWEEN '$fecha_inicial2' AND '$fecha_final') ORDER BY fecha, tipo_documento, numero_documento, condicion_actividad, tipo";

				$suma_anterior_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
				$resta_anterior_activos = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
				$suma_anterior_anul     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul);
				$resta_anterior_anul    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul);
				$vista                  = $this->v_cstd_mov_gral->execute($sql_vista);

				$suma_anterior_activos[0][0]['monto'];
				$resta_anterior_activos[0][0]['monto'];
				$suma_anterior_anul[0][0]['monto'];
				$resta_anterior_anul[0][0]['monto'];
				$total_anterior = (($suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto']) + $resta_anterior_anul[0][0]['monto']) - $suma_anterior_anul[0][0]['monto'];

				$this->set('estilo_reporte',$por_ano);//Como se imprime si por mes o por rango de fecha.
				$this->set('fecha_inicial2',$fecha_inicial2);
				$this->set('fecha_final',$fecha_final);
				$this->set('total_anterior',$total_anterior);
				$this->set('vista',$vista);
				$this->set('titulo_reporte','MOVIMIENTOS DEL '.$fecha_inicial2.' AL '.$fecha_final.'  -  ANULADOS');
	}
	$this->set('cod_entidad_bancaria',$cod_entidad_bancaria);
	$this->set('cod_sucursal',$cod_sucursal);
	$this->set('cuenta_bancaria',$cuenta_bancaria);
	$this->set('concepto_manejo_cuenta',$concep_manejo_cuenta[0]['cstd02_cuentas_bancarias']['concepto_manejo']);
	$this->set('ent', $ent[0]['cstd01_entidades_bancarias']['denominacion']);
	$this->set('suc', $suc[0]['cstd01_sucursales_bancarias']['denominacion']);

	$this->reporte_movimiento_general_anulados_2();
	$this->render("reporte_movimiento_general_anulados_2");
}

}//generar_reporte_movimiento_general



function reporte_movimiento_general_activos_2(){
	$this->layout="pdf";
	//echo "vista activos";
}


function reporte_movimiento_general_anulados_2(){
	$this->layout="pdf";
	//echo "vista anulados";
}

function conciliacion_cuentas_bancarias_form(){
	$this->layout ="ajax";
	$ano = $this->ano_ejecucion();

	 	$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'direccion_superior');

   //$entidades=$this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='704'");

	if($cont==0){
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','');
		$this->set('cargo_tercera_firma','');
		$this->set('tipo_doc_anul',704);
		$this->set('firma',1);

	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=704");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('tipo_doc_anul',704);
		$this->set('firma',2);
	}

	$this->set('ano_movimiento', $ano);
  //$this->set('direccion_superior',$entidades);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}

function conciliacion_cuentas_bancarias_pdf(){
	$this->layout ="pdf";
	$cp = $this->verifica_SS(1);
  	$ce = $this->verifica_SS(2);
  	$cti = $this->verifica_SS(3);
  	$ci = $this->verifica_SS(4);
  	$cd = $this->verifica_SS(5);


	$ano_movimiento       = $this->data['cstp04_movimientos_generales']['ano_1'];
	$cod_entidad_bancaria = $this->data['cstp04_movimientos_generales']['cod_entidad_bancaria'];
	$cod_sucursal         = $this->data['cstp04_movimientos_generales']['cod_sucursal_bancaria'];
	$cuenta_bancaria      = $this->data['cstp04_movimientos_generales']['cuenta_bancaria'];
	$condicion_sin_ano    = "a.cod_presi = '$cp' AND a.cod_entidad = '$ce' AND a.cod_tipo_inst = '$cti' AND a.cod_inst = '$ci' AND a.cod_dep = '$cd' and a.cod_entidad_bancaria=".$cod_entidad_bancaria." and a.cod_sucursal=".$cod_sucursal." and a.cuenta_bancaria='$cuenta_bancaria'";

	$fecha_conciliacion     = $this->data['cstp04_movimientos_generales']['fecha_final'];
	$cadena_fecha = split("/",$fecha_conciliacion);
   	$dia=(int) $cadena_fecha[0];
	$mes=(int) $cadena_fecha[1];
   	$ano=(int) $cadena_fecha[2];
	$ultimo_dia = date('d/m/Y',mktime(0, 0, 0, $mes, 0, $ano_movimiento));
	$primer_dia = date('d/m/Y',mktime(0, 0, 0, $mes, 1, $ano_movimiento));

	$sql = "SELECT
			a.cod_presi,
			a.cod_entidad,
			a.cod_tipo_inst,
			a.cod_inst,
			a.cod_dep,
			(SELECT c.denominacion FROM arrd05 c WHERE c.cod_presi=a.cod_presi AND c.cod_entidad=a.cod_entidad AND c.cod_tipo_inst=a.cod_tipo_inst AND c.cod_inst=a.cod_inst AND c.cod_dep=a.cod_dep) as deno_dep,
			a.cod_entidad_bancaria,
			(SELECT d.denominacion FROM cstd01_entidades_bancarias d WHERE d.cod_entidad_bancaria=a.cod_entidad_bancaria) as deno_entidad_bancaria,
			a.cod_sucursal,
			a.cuenta_bancaria,
			(SELECT Sum(monto) FROM v_cstd_mov_gral b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_entidad_bancaria=a.cod_entidad_bancaria AND b.cod_sucursal=a.cod_sucursal AND b.cuenta_bancaria=a.cuenta_bancaria AND (b.tipo_documento=1 or b.tipo_documento=2) AND ((b.fecha_documento BETWEEN '01/01/2000' AND '$ultimo_dia') AND (b.fecha_proceso_anulacion < '01/01/2000' OR b.fecha_proceso_anulacion >= '$ultimo_dia'))) as suma_anterior,
			(SELECT Sum(monto) FROM v_cstd_mov_gral b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_entidad_bancaria=a.cod_entidad_bancaria AND b.cod_sucursal=a.cod_sucursal AND b.cuenta_bancaria=a.cuenta_bancaria AND (b.tipo_documento=3 or b.tipo_documento=4) AND ((b.fecha_documento BETWEEN '01/01/2000' AND '$ultimo_dia') AND (b.fecha_proceso_anulacion < '01/01/2000' OR b.fecha_proceso_anulacion >= '$ultimo_dia'))) as resta_anterior,
			(SELECT Sum(monto) FROM v_cstd_mov_gral b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_entidad_bancaria=a.cod_entidad_bancaria AND b.cod_sucursal=a.cod_sucursal AND b.cuenta_bancaria=a.cuenta_bancaria AND (b.tipo_documento=1 or b.tipo_documento=2) AND ((b.fecha_documento BETWEEN '$primer_dia' AND '$fecha_conciliacion') AND (b.fecha_proceso_anulacion < '01/01/2000' OR b.fecha_proceso_anulacion >= '$fecha_conciliacion'))) as depositos_nc,
			(SELECT Sum(monto) FROM v_cstd_mov_gral b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_entidad_bancaria=a.cod_entidad_bancaria AND b.cod_sucursal=a.cod_sucursal AND b.cuenta_bancaria=a.cuenta_bancaria AND b.tipo_documento=3 AND ((b.fecha_documento BETWEEN '$primer_dia' AND '$fecha_conciliacion') AND (b.fecha_proceso_anulacion < '01/01/2000' OR b.fecha_proceso_anulacion >= '$fecha_conciliacion'))) as notas_debito,
			(SELECT Sum(monto) FROM v_cstd_mov_gral b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_entidad_bancaria=a.cod_entidad_bancaria AND b.cod_sucursal=a.cod_sucursal AND b.cuenta_bancaria=a.cuenta_bancaria AND b.tipo_documento=4 AND ((b.fecha_documento BETWEEN '$primer_dia' AND '$fecha_conciliacion') AND (b.fecha_proceso_anulacion < '01/01/2000' OR b.fecha_proceso_anulacion >= '$fecha_conciliacion'))) as cheques_todos,
			(SELECT Sum(monto) FROM v_cstd_mov_gral b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_entidad_bancaria=a.cod_entidad_bancaria AND b.cod_sucursal=a.cod_sucursal AND b.cuenta_bancaria=a.cuenta_bancaria AND b.tipo_documento=4 AND b.status=4 AND ((b.fecha_documento BETWEEN '$primer_dia' AND '$fecha_conciliacion') AND (b.fecha_proceso_anulacion < '01/01/2000' OR b.fecha_proceso_anulacion >= '$fecha_conciliacion'))) as cheques_cancelados
		FROM
			cstd02_cuentas_bancarias a
		WHERE
			".$condicion_sin_ano.";";

	$vector_cuentas = $this->v_cstd_mov_gral->execute($sql);

	$sql_transito = "SELECT * FROM v_cstd_mov_gral a WHERE ".$condicion_sin_ano." AND a.tipo_documento=4 AND (a.status=1 or a.status=2) AND ((a.fecha_documento BETWEEN '$primer_dia' AND '$fecha_conciliacion') AND (a.fecha_proceso_anulacion < '01/01/2000' OR a.fecha_proceso_anulacion >= '$fecha_conciliacion')) and a.condicion_actividad=1 ORDER BY a.fecha_documento";
	$vector_transito = $this->v_cstd_mov_gral->execute($sql_transito);

	$firmantes= $this->cugd07_firmas_oficio_anulacion->execute("select * from cugd07_firmas_oficio_anulacion where ".$this->SQLCA()." and tipo_documento=704");
	$this->Session->write('firma1',$firmantes[0][0]['nombre_primera_firma']);
	$this->Session->write('firma2',$firmantes[0][0]['nombre_segunda_firma']);
	$this->Session->write('firma3',$firmantes[0][0]['nombre_tercera_firma']);
	$this->Session->write('cargo1',$firmantes[0][0]['cargo_primera_firma']);
	$this->Session->write('cargo2',$firmantes[0][0]['cargo_segunda_firma']);
	$this->Session->write('cargo3',$firmantes[0][0]['cargo_tercera_firma']);

	$this->set('vector_cuentas',$vector_cuentas);
	$this->set('vector_transito',$vector_transito);
	$this->set('primer_dia',$primer_dia);
	$this->set('ultimo_dia',$ultimo_dia);
	$this->set('fecha_conciliacion',$fecha_conciliacion);
	$this->set('ano_movimiento',$ano_movimiento);
	$this->set('cod_entidad_bancaria',$cod_entidad_bancaria);
	$this->set('cod_sucursal',$cod_sucursal);
	$this->set('cuenta_bancaria',$cuenta_bancaria);
}

function firmantes_conciliacion_cuentas_bancarias(){
	$this->layout="ajax";
	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	if(!empty($this->data['cstp04_movimientos_generales']['nombre_primera_firma']) && !empty($this->data['cstp04_movimientos_generales']['cargo_primera_firma']) && !empty($this->data['cstp04_movimientos_generales']['nombre_segunda_firma']) && !empty($this->data['cstp04_movimientos_generales']['cargo_segunda_firma']) && !empty($this->data['cstp04_movimientos_generales']['nombre_tercera_firma']) && !empty($this->data['cstp04_movimientos_generales']['cargo_tercera_firma'])){
		$tipo_doc_anul = $this->data['cugp03_acta_anulacion']['tipo_doc_anul'];

		$tipo_doc_anul=704;
		$nombre_primera_firma = $this->data['cstp04_movimientos_generales']['nombre_primera_firma'];
		$cargo_primera_firma  = $this->data['cstp04_movimientos_generales']['cargo_primera_firma'];
		$nombre_segunda_firma = $this->data['cstp04_movimientos_generales']['nombre_segunda_firma'];
		$cargo_segunda_firma  = $this->data['cstp04_movimientos_generales']['cargo_segunda_firma'];
		$nombre_tercera_firma = $this->data['cstp04_movimientos_generales']['nombre_tercera_firma'];;
		$cargo_tercera_firma  = $this->data['cstp04_movimientos_generales']['cargo_tercera_firma'];;
		$nombre_cuarta_firma = "0";
		$cargo_cuarta_firma  = "0";

		$primera_cc = "0";
		$segunda_cc = "0";
		$tercera_cc = "0";
		$cuarta_cc  = "0";
		$quinta_cc  = "0";
		$sexta_cc   = "0";
		$septima_cc = "0";
		$octava_cc  = "0";


		$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='704'");
    	if($cont==0){
    		$insert = "INSERT INTO cugd07_firmas_oficio_anulacion VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc_anul,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma', '$nombre_cuarta_firma', '$cargo_cuarta_firma', '$primera_cc', '$segunda_cc', '$tercera_cc', '$cuarta_cc', '$quinta_cc', '$sexta_cc')";
			$this->cugd07_firmas_oficio_anulacion->execute($insert);

    	}else{
			$insert = "UPDATE cugd07_firmas_oficio_anulacion set nombre_primera_firma='$nombre_primera_firma',cargo_primera_firma='$cargo_primera_firma',nombre_segunda_firma='$nombre_segunda_firma',cargo_segunda_firma='$cargo_segunda_firma',nombre_tercera_firma='$nombre_tercera_firma',cargo_tercera_firma='$cargo_tercera_firma' WHERE ".$this->SQLCA()." and tipo_documento=704";
			$this->cugd07_firmas_oficio_anulacion->execute($insert);
    	}



		$this->set('Message_existe','Las firmas fuer&oacute;n registradas correctamente');
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=704");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('tipo_doc_anul',704);
		$this->set('firma',2);

	}else{
		$this->set('errorMessage','debe ingresar las firmas y cargos');

	}

	echo "<script>document.getElementById('b_modificar_firmas').disabled=false;</script>";

}



function firmantes_libro_cuentas_bancarias(){
	$this->layout="ajax";
	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	if(!empty($this->data['cstp04_movimientos_generales']['nombre_primera_firma']) && !empty($this->data['cstp04_movimientos_generales']['cargo_primera_firma']) && !empty($this->data['cstp04_movimientos_generales']['nombre_segunda_firma']) && !empty($this->data['cstp04_movimientos_generales']['cargo_segunda_firma']) && !empty($this->data['cstp04_movimientos_generales']['nombre_tercera_firma']) && !empty($this->data['cstp04_movimientos_generales']['cargo_tercera_firma'])){
		$tipo_doc_anul = $this->data['cugp03_acta_anulacion']['tipo_doc_anul'];

		$tipo_doc_anul=705;// Firmantes del libro de cuentas bancarias
		$nombre_primera_firma = $this->data['cstp04_movimientos_generales']['nombre_primera_firma'];
		$cargo_primera_firma  = $this->data['cstp04_movimientos_generales']['cargo_primera_firma'];
		$nombre_segunda_firma = $this->data['cstp04_movimientos_generales']['nombre_segunda_firma'];
		$cargo_segunda_firma  = $this->data['cstp04_movimientos_generales']['cargo_segunda_firma'];
		$nombre_tercera_firma = $this->data['cstp04_movimientos_generales']['nombre_tercera_firma'];;
		$cargo_tercera_firma  = $this->data['cstp04_movimientos_generales']['cargo_tercera_firma'];;
		$nombre_cuarta_firma = "0";
		$cargo_cuarta_firma  = "0";

		$primera_cc = $this->data['cstp04_movimientos_generales']['primera_copia'];// Cedula de identidad del primer firmante
		$segunda_cc = $this->data['cstp04_movimientos_generales']['segunda_copia'];// Cedula de identidad del segundo firmante
		$tercera_cc = $this->data['cstp04_movimientos_generales']['tercera_copia'];// Cedula de identidad del tercer firmante
		$cuarta_cc  = "0";
		$quinta_cc  = "0";
		$sexta_cc   = "0";
		$septima_cc = "0";
		$octava_cc  = "0";


		$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='705'");
    	if($cont==0){
    		$insert = "INSERT INTO cugd07_firmas_oficio_anulacion VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc_anul,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma', '$primera_cc', '$segunda_cc', '$tercera_cc', '$cuarta_cc', '$quinta_cc', '$sexta_cc')";
			$this->cugd07_firmas_oficio_anulacion->execute($insert);

    	}else{
			$insert = "UPDATE cugd07_firmas_oficio_anulacion set nombre_primera_firma='$nombre_primera_firma',cargo_primera_firma='$cargo_primera_firma',nombre_segunda_firma='$nombre_segunda_firma',cargo_segunda_firma='$cargo_segunda_firma',nombre_tercera_firma='$nombre_tercera_firma',cargo_tercera_firma='$cargo_tercera_firma',primera_copia='$primera_cc',segunda_copia='$segunda_cc',tercera_copia='$tercera_cc' WHERE ".$this->SQLCA()." and tipo_documento=705";
			$this->cugd07_firmas_oficio_anulacion->execute($insert);
    	}

		$this->set('Message_existe','Las firmas fuer&oacute;n registradas correctamente');
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=705");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('primera_copia',$firmantes[0]['cugd07_firmas_oficio_anulacion']['primera_copia']);
		$this->set('segunda_copia',$firmantes[0]['cugd07_firmas_oficio_anulacion']['segunda_copia']);
		$this->set('tercera_copia',$firmantes[0]['cugd07_firmas_oficio_anulacion']['tercera_copia']);
		$this->set('tipo_doc_anul',705);
		$this->set('firma',2);

	}else{
		$this->set('errorMessage','debe ingresar las firmas y cargos');

		$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='705'");
		if($cont==0){
			$this->set('nombre_primera_firma','');
			$this->set('cargo_primera_firma','');
			$this->set('nombre_segunda_firma','');
			$this->set('cargo_segunda_firma','');
			$this->set('nombre_tercera_firma','');
			$this->set('cargo_tercera_firma','');
			$this->set('primera_copia','');
			$this->set('segunda_copia','');
			$this->set('tercera_copia','');
			$this->set('tipo_doc_anul',705);
			$this->set('firma',1);

		}else{
			$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=705");
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('primera_copia',$firmantes[0]['cugd07_firmas_oficio_anulacion']['primera_copia']);
			$this->set('segunda_copia',$firmantes[0]['cugd07_firmas_oficio_anulacion']['segunda_copia']);
			$this->set('tercera_copia',$firmantes[0]['cugd07_firmas_oficio_anulacion']['tercera_copia']);
			$this->set('tipo_doc_anul',705);
			$this->set('firma',2);
		}

	}
	echo "<script>document.getElementById('b_modificar_firmas').disabled=false;</script>";
}



function verificar_notas_debitos() {

	$cp		= $this->verifica_SS(1);
    $ce		= $this->verifica_SS(2);
    $cti	= $this->verifica_SS(3);
    $ci		= $this->verifica_SS(4);
    $cd		= 1;

	$condicion = "cod_presi='$cp' AND cod_entidad = '$ce' AND cod_tipo_inst = '$cti' AND cod_inst = '$ci'";

	$nota_deb_cuerpo   = $this->cstd09_notadebito_cuerpo_pago->findAll($condicion);
	$nota_deb_especial = $this->cstd09_notadebito_cuerpo->findAll($condicion);


	echo "<br /><br />Verificando notas de debito especiales<br /><br />";

	foreach($nota_deb_especial as $especial){
		$cod_presi = $especial['cstd09_notadebito_cuerpo']['cod_presi'];
		$cod_entidad = $especial['cstd09_notadebito_cuerpo']['cod_entidad'];
		$cod_tipo_inst = $especial['cstd09_notadebito_cuerpo']['cod_tipo_inst'];
		$cod_inst = $especial['cstd09_notadebito_cuerpo']['cod_inst'];
		$cod_dep = $especial['cstd09_notadebito_cuerpo']['cod_dep'];
		$ano_movimiento = $especial['cstd09_notadebito_cuerpo']['ano_movimiento'];
		$cod_entidad_bancaria = $especial['cstd09_notadebito_cuerpo']['cod_entidad_bancaria'];
		$cod_sucursal = $especial['cstd09_notadebito_cuerpo']['cod_sucursal'];
		$cuenta_bancaria = $especial['cstd09_notadebito_cuerpo']['cuenta_bancaria'];
		$tipo_documento = $especial['cstd09_notadebito_cuerpo']['tipo_documento'];
		$numero_documento = $especial['cstd09_notadebito_cuerpo']['numero_documento'];
		$fecha_nota_debito = $especial['cstd09_notadebito_cuerpo']['fecha_nota_debito'];

		$sql_deb_especial = "cod_presi = '$cod_presi' AND
							cod_entidad = '$cod_entidad' AND
							cod_tipo_inst = '$cod_tipo_inst' AND
							cod_inst = '$cod_inst' AND
							cod_dep = '$cod_dep' AND
							ano_movimiento = '$ano_movimiento' AND
							cod_entidad_bancaria = '$cod_entidad_bancaria' AND
							cod_sucursal = '$cod_sucursal' AND
							cuenta_bancaria = '$cuenta_bancaria' AND
							tipo_documento = '$tipo_documento' AND
							numero_documento = '$numero_documento'";

		if($this->cstd04_movimientos_generales->findCount($sql_deb_especial) == 0){
			echo "<br /><br /> NO EXISTE EN MOV MANUALES: ".$sql_deb_especial;
			echo "<br />Fecha: ".$fecha_nota_debito;
			if($this->cstd03_movimientos_manuales->findCount($sql_deb_especial) == 0){
				echo "<br /><br /> NO EXISTE EN MOV GENERALES: ".$sql_deb_especial;
				echo "<br />Fecha: ".$fecha_nota_debito;
			}
		}
	}


	echo "<br /><br />Verificando notas de debito cuerpo<br /><br />";


	foreach($nota_deb_cuerpo as $cuerpo){
		$cod_presi = $cuerpo['cstd09_notadebito_cuerpo_pago']['cod_presi'];
		$cod_entidad = $cuerpo['cstd09_notadebito_cuerpo_pago']['cod_entidad'];
		$cod_tipo_inst = $cuerpo['cstd09_notadebito_cuerpo_pago']['cod_tipo_inst'];
		$cod_inst = $cuerpo['cstd09_notadebito_cuerpo_pago']['cod_inst'];
		$cod_dep = $cuerpo['cstd09_notadebito_cuerpo_pago']['cod_dep'];
		$ano_movimiento = $cuerpo['cstd09_notadebito_cuerpo_pago']['ano_movimiento'];
		$cod_entidad_bancaria = $cuerpo['cstd09_notadebito_cuerpo_pago']['cod_entidad_bancaria'];
		$cod_sucursal = $cuerpo['cstd09_notadebito_cuerpo_pago']['cod_sucursal'];
		$cuenta_bancaria = $cuerpo['cstd09_notadebito_cuerpo_pago']['cuenta_bancaria'];
		$numero_debito = $cuerpo['cstd09_notadebito_cuerpo_pago']['numero_debito'];
		$condicion_actividad = $cuerpo['cstd09_notadebito_cuerpo_pago']['condicion_actividad'];
		$fecha_debito = $cuerpo['cstd09_notadebito_cuerpo_pago']['fecha_debito'];


		$sql_deb_cuerpo = "cod_presi = '$cod_presi' AND
							cod_entidad = '$cod_entidad' AND
							cod_tipo_inst = '$cod_tipo_inst' AND
							cod_inst = '$cod_inst' AND
							cod_dep = '$cod_dep' AND
							ano_movimiento = '$ano_movimiento' AND
							cod_entidad_bancaria = '$cod_entidad_bancaria' AND
							cod_sucursal = '$cod_sucursal' AND
							cuenta_bancaria = '$cuenta_bancaria' AND
							tipo_documento = '3' AND
							numero_documento = '$numero_debito'";

		if($this->cstd04_movimientos_generales->findCount($sql_deb_cuerpo) == 0){
			echo "<br /><br /> NO EXISTE EN MOV MANUALES: ".$sql_deb_cuerpo."<br />Condicion actividad: ".$condicion_actividad;
			echo "<br />Fecha: ".$fecha_debito;
			if($this->cstd03_movimientos_manuales->findCount($sql_deb_cuerpo) == 0){
				echo "<br /><br /> NO EXISTE EN MOV GENERALES: ".$sql_deb_cuerpo."<br />Condicion actividad: ".$condicion_actividad;
				echo "<br />Fecha: ".$fecha_debito;
			}
		}

	}

}


function conciliacion_bancaria_saldos_encontrados ($var = null){
	$this->layout ="ajax";
	$ano = $this->ano_ejecucion();

	$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'direccion_superior');

  //$entidades=$this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='704'");

	if($cont==0){
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','');
		$this->set('cargo_tercera_firma','');
		$this->set('tipo_doc_anul',704);
		$this->set('firma',1);
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=704");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('tipo_doc_anul',704);
		$this->set('firma',2);
	}

	$this->set('ano_movimiento', $ano);
  //$this->set('direccion_superior',$entidades);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}

function conciliacion_bancaria_saldos_encontrados_pdf(){
	$this->layout ="pdf";
	$cp = $this->verifica_SS(1);
  	$ce = $this->verifica_SS(2);
  	$cti = $this->verifica_SS(3);
  	$ci = $this->verifica_SS(4);
  	$cd = $this->verifica_SS(5);

	$ano_movimiento       = $this->data['cstp04_movimientos_generales']['ano_1'];
	$cod_entidad_bancaria = $this->data['cstp04_movimientos_generales']['cod_entidad_bancaria'];
	$cod_sucursal         = $this->data['cstp04_movimientos_generales']['cod_sucursal_bancaria'];
	$cuenta_bancaria      = $this->data['cstp04_movimientos_generales']['cuenta_bancaria'];
	$fecha_conciliacion   = $this->data['cstp04_movimientos_generales']['fecha_final'];
	$anexo   			  = 0;
	$condicion_sin_ano    = "a.cod_presi = $cp AND a.cod_entidad = $ce AND a.cod_tipo_inst = $cti AND a.cod_inst = $ci AND a.cod_dep = $cd and a.cod_entidad_bancaria=".$cod_entidad_bancaria." and a.cod_sucursal=".$cod_sucursal." and a.cuenta_bancaria='$cuenta_bancaria'";
	$condicion_sin_ano_b  = "b.cod_presi = $cp AND b.cod_entidad = $ce AND b.cod_tipo_inst = $cti AND b.cod_inst = $ci AND b.cod_dep = $cd and b.cod_entidad_bancaria=".$cod_entidad_bancaria." and b.cod_sucursal=".$cod_sucursal." and b.cuenta_bancaria='$cuenta_bancaria'";

	// Para calcular el saldo segun estado de cuenta.
	$suma_dep_nc = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as suma_dep_nc FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$cod_entidad_bancaria' AND cod_sucursal='$cod_sucursal' AND cuenta_bancaria='$cuenta_bancaria' AND (tipo_documento='1' OR  tipo_documento='2') AND (a.fecha_documento BETWEEN '01/01/1900' AND '$fecha_conciliacion')");
	$suma_che_nd = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as suma_che_nd FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$cod_entidad_bancaria' AND cod_sucursal='$cod_sucursal' AND cuenta_bancaria='$cuenta_bancaria' AND (tipo_documento='3' OR  tipo_documento='4') AND (a.fecha_documento BETWEEN '01/01/1900' AND '$fecha_conciliacion')");
	$this->set('suma_dep_nc', isset($suma_dep_nc[0][0]['suma_dep_nc']) ? $suma_dep_nc[0][0]['suma_dep_nc'] : 0);
	$this->set('suma_che_nd', isset($suma_che_nd[0][0]['suma_che_nd']) ? $suma_che_nd[0][0]['suma_che_nd'] : 0);

	$sql_depositos_transito = "SELECT a.cod_presi,
			                        a.cod_entidad,
			                        a.cod_tipo_inst,
			                        a.cod_inst,
			                        a.cod_dep,
			                        a.ano_movimiento,
			                        a.cod_entidad_bancaria,
			                        a.cod_sucursal,
			                        a.cuenta_bancaria,
			                        a.tipo_documento,
			                        a.numero_documento,
			                        a.fecha_documento,
			                        a.monto,
			                        a.beneficiario,
			                        a.concepto
							FROM v_cstd04_movimientos_generales a
							WHERE ".$condicion_sin_ano." AND (a.fecha_documento
									BETWEEN '01/01/1900' AND '$fecha_conciliacion') AND
		                            a.condicion_actividad=1 AND a.tipo_documento=1 AND
		                    NOT EXISTS (SELECT * FROM cstd05_estado_cuentas b
		                    WHERE   a.cod_presi = b.cod_presi AND
									a.cod_entidad     = b.cod_entidad AND
									a.cod_tipo_inst   = b.cod_tipo_inst AND
									a.cod_inst        = b.cod_inst AND
									a.cod_dep 	      = b.cod_dep AND
									a.cod_entidad_bancaria = b.cod_entidad_bancaria AND
									a.cod_sucursal    = b.cod_sucursal AND
									a.cuenta_bancaria = b.cuenta_bancaria AND
									a.tipo_documento  = b.tipo_documento AND
									a.numero_documento= b.numero_documento) ORDER BY a.tipo_documento, a.fecha_documento, a.numero_documento";


	$sql_notascredito_transito = "SELECT a.cod_presi,
			                        a.cod_entidad,
			                        a.cod_tipo_inst,
			                        a.cod_inst,
			                        a.cod_dep,
			                        a.ano_movimiento,
			                        a.cod_entidad_bancaria,
			                        a.cod_sucursal,
			                        a.cuenta_bancaria,
			                        a.tipo_documento,
			                        a.numero_documento,
			                        a.fecha_documento,
			                        a.monto,
			                        a.beneficiario,
			                        a.concepto
							FROM v_cstd04_movimientos_generales a
							WHERE ".$condicion_sin_ano." AND (a.fecha_documento
									BETWEEN '01/01/1900' AND '$fecha_conciliacion') AND
		                            a.condicion_actividad=1 AND a.tipo_documento=2 AND
		                    NOT EXISTS (SELECT * FROM cstd05_estado_cuentas b
		                    WHERE   a.cod_presi = b.cod_presi AND
									a.cod_entidad     = b.cod_entidad AND
									a.cod_tipo_inst   = b.cod_tipo_inst AND
									a.cod_inst        = b.cod_inst AND
									a.cod_dep 	      = b.cod_dep AND
									a.cod_entidad_bancaria = b.cod_entidad_bancaria AND
									a.cod_sucursal    = b.cod_sucursal AND
									a.cuenta_bancaria = b.cuenta_bancaria AND
									a.tipo_documento  = b.tipo_documento AND
									a.numero_documento= b.numero_documento) ORDER BY a.tipo_documento, a.fecha_documento, a.numero_documento";


	$sql_abonos_indebidos = "SELECT a.cod_presi,
			                        a.cod_entidad,
			                        a.cod_tipo_inst,
			                        a.cod_inst,
			                        a.cod_dep,
			                        a.ano_movimiento,
			                        a.cod_entidad_bancaria,
			                        a.cod_sucursal,
			                        a.cuenta_bancaria,
			                        a.tipo_documento,
			                        a.numero_documento,
			                        a.fecha_documento,
			                        a.monto
							FROM cstd05_estado_cuentas a
							WHERE ".$condicion_sin_ano."  AND (a.fecha_documento
									BETWEEN '01/01/1900' AND '$fecha_conciliacion') AND
		                            (a.tipo_documento=1 OR a.tipo_documento=2) AND
		                    NOT EXISTS (SELECT * FROM v_cstd04_movimientos_generales b
		                    WHERE   a.cod_presi = b.cod_presi AND
									a.cod_entidad     = b.cod_entidad AND
									a.cod_tipo_inst   = b.cod_tipo_inst AND
									a.cod_inst        = b.cod_inst AND
									a.cod_dep 	      = b.cod_dep AND
									a.cod_entidad_bancaria = b.cod_entidad_bancaria AND
									a.cod_sucursal    = b.cod_sucursal AND
									a.cuenta_bancaria = b.cuenta_bancaria AND
									a.tipo_documento  = b.tipo_documento AND
									a.numero_documento= b.numero_documento)";


		$sql_dif_abonos_cargos = "SELECT
									a.cod_presi,
									a.cod_entidad,
									a.cod_tipo_inst,
									a.cod_inst,
									a.cod_dep,
									a.ano_movimiento,
									a.cod_entidad_bancaria,
									a.cod_sucursal,
									a.cuenta_bancaria,
									a.tipo_documento,
									a.numero_documento,
									a.fecha_documento,
									b.beneficiario,
									b.concepto,
									a.monto AS monto_estadocuenta,
									b.monto AS monto_movgenerales
							  FROM  cstd05_estado_cuentas a, v_cstd04_movimientos_generales b
						      WHERE a.cod_presi       = b.cod_presi AND
									a.cod_entidad     = b.cod_entidad AND
									a.cod_tipo_inst   = b.cod_tipo_inst AND
									a.cod_inst        = b.cod_inst AND
									a.cod_dep 	  	  = b.cod_dep AND
									a.monto           <> b.monto AND
									a.cod_entidad_bancaria = b.cod_entidad_bancaria AND
									a.cod_sucursal    = b.cod_sucursal AND
									a.cuenta_bancaria = b.cuenta_bancaria AND
									a.tipo_documento  = b.tipo_documento AND
									a.numero_documento= b.numero_documento
								AND ".$condicion_sin_ano."  AND
									(a.fecha_documento BETWEEN '01/01/1900' AND '$fecha_conciliacion') AND
									(b.fecha_proceso_anulacion = '01/01/1900' OR b.fecha_proceso_anulacion = '01/01/1997' OR b.fecha_proceso_anulacion > '$fecha_conciliacion') ORDER BY a.tipo_documento, a.fecha_documento, a.numero_documento";



		$sql_dif_abonos_indebidos = "SELECT
									a.cod_presi,
									a.cod_entidad,
									a.cod_tipo_inst,
									a.cod_inst,
									a.cod_dep,
									a.ano_movimiento,
									a.cod_entidad_bancaria,
									a.cod_sucursal,
									a.cuenta_bancaria,
									a.tipo_documento,
									a.numero_documento,
									a.fecha_documento,
									b.beneficiario,
									b.concepto,
									a.monto AS monto_estadocuenta,
									b.monto AS monto_movgenerales
							  FROM  cstd05_estado_cuentas a, v_cstd04_movimientos_generales b
						      WHERE a.cod_presi       = b.cod_presi AND
									a.cod_entidad     = b.cod_entidad AND
									a.cod_tipo_inst   = b.cod_tipo_inst AND
									a.cod_inst        = b.cod_inst AND
									a.cod_dep 	  	  = b.cod_dep AND
									(a.tipo_documento=1 OR a.tipo_documento=2) AND
									a.monto           <> b.monto AND
									a.cod_entidad_bancaria = b.cod_entidad_bancaria AND
									a.cod_sucursal    = b.cod_sucursal AND
									a.cuenta_bancaria = b.cuenta_bancaria AND
									a.tipo_documento  = b.tipo_documento AND
									a.numero_documento= b.numero_documento
								AND ".$condicion_sin_ano."  AND
									(a.fecha_documento BETWEEN '01/01/1900' AND '$fecha_conciliacion') AND
									(b.fecha_proceso_anulacion = '01/01/1900' OR b.fecha_proceso_anulacion = '01/01/1997' OR b.fecha_proceso_anulacion > '$fecha_conciliacion') ORDER BY a.tipo_documento, a.fecha_documento, a.numero_documento";


	$sql_dif_cargos_indebidos = "SELECT
									a.cod_presi,
									a.cod_entidad,
									a.cod_tipo_inst,
									a.cod_inst,
									a.cod_dep,
									a.ano_movimiento,
									a.cod_entidad_bancaria,
									a.cod_sucursal,
									a.cuenta_bancaria,
									a.tipo_documento,
									a.numero_documento,
									a.fecha_documento,
									b.beneficiario,
									b.concepto,
									a.monto AS monto_estadocuenta,
									b.monto AS monto_movgenerales
							  FROM  cstd05_estado_cuentas a, v_cstd04_movimientos_generales b
						      WHERE a.cod_presi       = b.cod_presi AND
									a.cod_entidad     = b.cod_entidad AND
									a.cod_tipo_inst   = b.cod_tipo_inst AND
									a.cod_inst        = b.cod_inst AND
									a.cod_dep 	  	  = b.cod_dep AND
									(a.tipo_documento=3 OR a.tipo_documento=4) AND
									a.monto           <> b.monto AND
									a.cod_entidad_bancaria = b.cod_entidad_bancaria AND
									a.cod_sucursal    = b.cod_sucursal AND
									a.cuenta_bancaria = b.cuenta_bancaria AND
									a.tipo_documento  = b.tipo_documento AND
									a.numero_documento= b.numero_documento
								AND ".$condicion_sin_ano."  AND
									(a.fecha_documento BETWEEN '01/01/1900' AND '$fecha_conciliacion') AND
									(b.fecha_proceso_anulacion = '01/01/1900' OR b.fecha_proceso_anulacion = '01/01/1997' OR b.fecha_proceso_anulacion > '$fecha_conciliacion') ORDER BY a.tipo_documento, a.fecha_documento, a.numero_documento";


	$sql_cargos_indebidos = "SELECT a.cod_presi,
			                        a.cod_entidad,
			                        a.cod_tipo_inst,
			                        a.cod_inst,
			                        a.cod_dep,
			                        a.ano_movimiento,
			                        a.cod_entidad_bancaria,
			                        a.cod_sucursal,
			                        a.cuenta_bancaria,
			                        a.tipo_documento,
			                        a.numero_documento,
			                        a.fecha_documento,
			                        a.monto
							FROM cstd05_estado_cuentas a
							WHERE ".$condicion_sin_ano."  AND (a.fecha_documento
									BETWEEN '01/01/1900' AND '$fecha_conciliacion') AND
		                            (a.tipo_documento=3 OR a.tipo_documento=4) AND
		                    NOT EXISTS (SELECT * FROM v_cstd04_movimientos_generales b
		                    WHERE   a.cod_presi = b.cod_presi AND
									a.cod_entidad     = b.cod_entidad AND
									a.cod_tipo_inst   = b.cod_tipo_inst AND
									a.cod_inst        = b.cod_inst AND
									a.cod_dep 	      = b.cod_dep AND
									a.cod_entidad_bancaria = b.cod_entidad_bancaria AND
									a.cod_sucursal    = b.cod_sucursal AND
									a.cuenta_bancaria = b.cuenta_bancaria AND
									a.tipo_documento  = b.tipo_documento AND
									a.numero_documento= b.numero_documento)";


	$sql_cheques_transito = "SELECT a.cod_presi,
			                        a.cod_entidad,
			                        a.cod_tipo_inst,
			                        a.cod_inst,
			                        a.cod_dep,
			                        a.ano_movimiento,
			                        a.cod_entidad_bancaria,
			                        a.cod_sucursal,
			                        a.cuenta_bancaria,
			                        a.tipo_documento,
			                        a.numero_documento,
			                        a.fecha_documento,
			                        a.monto,
			                        a.beneficiario,
			                        a.concepto
							FROM v_cstd04_movimientos_generales a
							WHERE ".$condicion_sin_ano." AND (a.fecha_documento
									BETWEEN '01/01/1900' AND '$fecha_conciliacion') AND
		                            a.condicion_actividad=1 AND a.tipo_documento=4 AND
		                    NOT EXISTS (SELECT * FROM cstd05_estado_cuentas b
		                    WHERE   a.cod_presi = b.cod_presi AND
									a.cod_entidad     = b.cod_entidad AND
									a.cod_tipo_inst   = b.cod_tipo_inst AND
									a.cod_inst        = b.cod_inst AND
									a.cod_dep 	      = b.cod_dep AND
									a.cod_entidad_bancaria = b.cod_entidad_bancaria AND
									a.cod_sucursal    = b.cod_sucursal AND
									a.cuenta_bancaria = b.cuenta_bancaria AND
									a.tipo_documento  = b.tipo_documento AND
									a.numero_documento= b.numero_documento) ORDER BY a.tipo_documento, a.fecha_documento, a.numero_documento";

	$sql_notadeb_transito = "SELECT a.cod_presi,
			                        a.cod_entidad,
			                        a.cod_tipo_inst,
			                        a.cod_inst,
			                        a.cod_dep,
			                        a.ano_movimiento,
			                        a.cod_entidad_bancaria,
			                        a.cod_sucursal,
			                        a.cuenta_bancaria,
			                        a.tipo_documento,
			                        a.numero_documento,
			                        a.fecha_documento,
			                        a.monto,
			                        a.beneficiario,
			                        a.concepto
							FROM v_cstd04_movimientos_generales a
							WHERE ".$condicion_sin_ano." AND (a.fecha_documento
									BETWEEN '01/01/1900' AND '$fecha_conciliacion') AND
		                            a.condicion_actividad=1 AND a.tipo_documento=3 AND
		                    NOT EXISTS (SELECT * FROM cstd05_estado_cuentas b
		                    WHERE   a.cod_presi = b.cod_presi AND
									a.cod_entidad     = b.cod_entidad AND
									a.cod_tipo_inst   = b.cod_tipo_inst AND
									a.cod_inst        = b.cod_inst AND
									a.cod_dep 	      = b.cod_dep AND
									a.cod_entidad_bancaria = b.cod_entidad_bancaria AND
									a.cod_sucursal    = b.cod_sucursal AND
									a.cuenta_bancaria = b.cuenta_bancaria AND
									a.tipo_documento  = b.tipo_documento AND
									a.numero_documento= b.numero_documento) ORDER BY a.tipo_documento, a.fecha_documento, a.numero_documento";


	$sql_movimientos_indebidos = "SELECT
									a.cod_presi,
									a.cod_entidad,
									a.cod_tipo_inst,
									a.cod_inst,
									a.cod_dep,
									a.ano_movimiento,
									a.cod_entidad_bancaria,
									a.cod_sucursal,
									a.cuenta_bancaria,
									a.tipo_documento,
									a.numero_documento,
									a.fecha_documento,
									b.beneficiario,
									b.concepto,
									a.monto AS monto_estadocuenta,
									b.monto AS monto_movgenerales

								FROM cstd05_estado_cuentas a

								INNER JOIN v_cstd04_movimientos_generales b

								ON 	a.cod_presi       = b.cod_presi AND
									a.cod_entidad     = b.cod_entidad AND
									a.cod_tipo_inst   = b.cod_tipo_inst AND
									a.cod_inst        = b.cod_inst AND
									a.cod_dep 	  	  = b.cod_dep AND
									a.monto           = b.monto AND
									a.cod_entidad_bancaria = b.cod_entidad_bancaria AND
									a.cod_sucursal    = b.cod_sucursal AND
									a.cuenta_bancaria = b.cuenta_bancaria AND
									a.tipo_documento  = b.tipo_documento AND
									a.numero_documento= b.numero_documento

								WHERE ".$condicion_sin_ano."  AND
									(a.fecha_documento BETWEEN '01/01/1900' AND '$fecha_conciliacion') AND
									(b.fecha_proceso_anulacion = '01/01/1900' OR b.fecha_proceso_anulacion = '01/01/1997' OR b.fecha_proceso_anulacion > '$fecha_conciliacion') ORDER BY a.tipo_documento, a.fecha_documento, a.numero_documento";


	$sql_abonos_indebidos_libro = "SELECT a.cod_presi,
			                        a.cod_entidad,
			                        a.cod_tipo_inst,
			                        a.cod_inst,
			                        a.cod_dep,
			                        a.ano_movimiento,
			                        a.cod_entidad_bancaria,
			                        a.cod_sucursal,
			                        a.cuenta_bancaria,
			                        a.tipo_documento,
			                        a.numero_documento,
			                        a.fecha_documento,
			                        a.monto,
			                        a.beneficiario,
			                        a.concepto
							FROM v_cstd04_movimientos_generales a
							WHERE ".$condicion_sin_ano." AND (a.fecha_documento
									BETWEEN '01/01/1900' AND '$fecha_conciliacion') AND
		                            a.condicion_actividad=1 AND (a.tipo_documento=1 OR a.tipo_documento=2) AND
		                    NOT EXISTS (SELECT * FROM cstd05_estado_cuentas b
		                    WHERE   a.cod_presi = b.cod_presi AND
									a.cod_entidad     = b.cod_entidad AND
									a.cod_tipo_inst   = b.cod_tipo_inst AND
									a.cod_inst        = b.cod_inst AND
									a.cod_dep 	      = b.cod_dep AND
									a.cod_entidad_bancaria = b.cod_entidad_bancaria AND
									a.cod_sucursal    = b.cod_sucursal AND
									a.cuenta_bancaria = b.cuenta_bancaria AND
									a.tipo_documento  = b.tipo_documento AND
									a.numero_documento= b.numero_documento) ORDER BY a.tipo_documento, a.fecha_documento, a.numero_documento";


	$sql_cargos_indebidos_libro = "SELECT a.cod_presi,
			                        a.cod_entidad,
			                        a.cod_tipo_inst,
			                        a.cod_inst,
			                        a.cod_dep,
			                        a.ano_movimiento,
			                        a.cod_entidad_bancaria,
			                        a.cod_sucursal,
			                        a.cuenta_bancaria,
			                        a.tipo_documento,
			                        a.numero_documento,
			                        a.fecha_documento,
			                        a.monto,
			                        a.beneficiario,
			                        a.concepto
							FROM v_cstd04_movimientos_generales a
							WHERE ".$condicion_sin_ano." AND (a.fecha_documento
									BETWEEN '01/01/1900' AND '$fecha_conciliacion') AND
		                            a.condicion_actividad=1 AND (a.tipo_documento=3 OR a.tipo_documento=4) AND
		                    NOT EXISTS (SELECT * FROM cstd05_estado_cuentas b
		                    WHERE   a.cod_presi = b.cod_presi AND
									a.cod_entidad     = b.cod_entidad AND
									a.cod_tipo_inst   = b.cod_tipo_inst AND
									a.cod_inst        = b.cod_inst AND
									a.cod_dep 	      = b.cod_dep AND
									a.cod_entidad_bancaria = b.cod_entidad_bancaria AND
									a.cod_sucursal    = b.cod_sucursal AND
									a.cuenta_bancaria = b.cuenta_bancaria AND
									a.tipo_documento  = b.tipo_documento AND
									a.numero_documento= b.numero_documento) ORDER BY a.tipo_documento, a.fecha_documento, a.numero_documento";

	$depositos_transito = $this->v_cstd04_movimientos_generales->execute($sql_depositos_transito);
	$cargos_indebidos   = $this->v_cstd04_movimientos_generales->execute($sql_cargos_indebidos);
	$notascred_transito = $this->v_cstd04_movimientos_generales->execute($sql_notascredito_transito);
	$cheques_transito = $this->v_cstd04_movimientos_generales->execute($sql_cheques_transito);
	$notadeb_transito = $this->v_cstd04_movimientos_generales->execute($sql_notadeb_transito);
	$abonos_indebidos   = $this->v_cstd04_movimientos_generales->execute($sql_abonos_indebidos);

	$dif_abonos_cargos   = $this->v_cstd04_movimientos_generales->execute($sql_dif_abonos_cargos);
	$dif_abonos_indebidos   = $this->v_cstd04_movimientos_generales->execute($sql_dif_abonos_indebidos);
	$dif_cargos_indebidos   = $this->v_cstd04_movimientos_generales->execute($sql_dif_cargos_indebidos);

	$abonos_indeb_libro   = $this->v_cstd04_movimientos_generales->execute($sql_abonos_indebidos_libro);
	$cargos_indeb_libro   = $this->v_cstd04_movimientos_generales->execute($sql_cargos_indebidos_libro);

	$depositos_nc_noregistradas = $abonos_indebidos;
	$cheques_nd_noregistradas   = $cargos_indebidos;

	$dif_depositos_nc = $dif_abonos_indebidos;
	$dif_cheques_nd = $dif_cargos_indebidos;

	// ARREGLO
	// variables que no se enviaban pero se majeban en la vista
	$this->set('dif_cargos_indebidos', $dif_cargos_indebidos);
	$this->set('dif_abonos_indebidos', $dif_abonos_indebidos);
	$this->set('dif_depositos_nc',$dif_depositos_nc);
	$this->set('dif_cheques_nd', $dif_cheques_nd);
	$this->set('dif_abonos_cargos',$dif_abonos_cargos);
//	var_dump($vmodulos);exit();

	//variables que ya se pasaban	
	$this->set('anexo', $anexo);
	$this->set('cuenta_bancaria', $cuenta_bancaria);
	$this->set('fecha_conciliacion', $fecha_conciliacion);
	$this->set('depositos_transito', $depositos_transito);
	$this->set('notascred_transito', $notascred_transito);
	$this->set('cargos_indebidos', $cargos_indebidos);
	$this->set('cheques_transito', $cheques_transito);
	$this->set('notadeb_transito', $notadeb_transito);
	$this->set('abonos_indebidos', $abonos_indebidos);

	$this->set('abonos_indeb_libro', $abonos_indeb_libro);
	$this->set('cargos_indeb_libro', $cargos_indeb_libro);
//pr($abonos_indeb_libro);
	$this->set('depositos_nc_noregistradas', $depositos_nc_noregistradas);
	$this->set('cheques_nd_noregistradas', $cheques_nd_noregistradas);

	$firmantes= $this->cugd07_firmas_oficio_anulacion->execute("select * from cugd07_firmas_oficio_anulacion where ".$this->SQLCA()." and tipo_documento=704");
	$this->set('firma1',$firmantes[0][0]['nombre_primera_firma']);
	$this->set('firma2',$firmantes[0][0]['nombre_segunda_firma']);
	$this->set('firma3',$firmantes[0][0]['nombre_tercera_firma']);
	$this->set('cargo1',$firmantes[0][0]['cargo_primera_firma']);
	$this->set('cargo2',$firmantes[0][0]['cargo_segunda_firma']);
	$this->set('cargo3',$firmantes[0][0]['cargo_tercera_firma']);

	$banco = $this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad_bancaria);
	$this->set('denominación_banco', $banco[0]['cstd01_entidades_bancarias']['denominacion']);


	// Calculo del saldo de tesoreria
	$condicion            = $this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'";
	$condicion_sin_ano    = $this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'";

	$sql_suma_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_conciliacion' AND tipo=1)";
	$sql_resta_anterior_activos = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_conciliacion' AND tipo=1)";
	$sql_suma_anterior_anul     = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_conciliacion' AND tipo=2)";
	$sql_resta_anterior_anul    = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion_sin_ano." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_conciliacion' AND tipo=2)";

	$suma_anterior_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
	$resta_anterior_activos = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
	$suma_anterior_anul     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul);
	$resta_anterior_anul    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul);

	$suma_anterior_activos[0][0]['monto'];
	$resta_anterior_activos[0][0]['monto'];
	$suma_anterior_anul[0][0]['monto'];
	$resta_anterior_anul[0][0]['monto'];
	$total_anterior = (($suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto']) + $resta_anterior_anul[0][0]['monto']) - $suma_anterior_anul[0][0]['monto'];
	$this->set('total_anterior_tesoreria', $total_anterior);
}






// ************** CONCILIACION BANCARIA **********************

function conciliacion_bancaria_metodo_comparativo ($var=null){
	set_time_limit(0);
	ini_set("memory_limit", "2048M");
	if($var=='si'){
		$this->layout ="ajax";
		$this->set('ir', $var);

		$entidades = $this->v_cstd05_estado_cuenta_vs_tesoreria->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd05_estado_cuenta_vs_tesoreria.cod_entidad_bancaria', '{n}.v_cstd05_estado_cuenta_vs_tesoreria.banco');
		$this->concatena_cuatro_digitos($entidades, 'entidades');
	}


	else if($var=='pdf'){
		$this->layout = "pdf";
		$cod_entidad = $this->data['conciliacion']['entidad'];
		$cod_sucursal = $this->data['conciliacion']['sucursal'];
		$cuenta_banc = $this->data['conciliacion']['cuenta'];
		$ano_movimiento = $this->data['conciliacion']['anos'];
		$mes_mov = $this->data['conciliacion']['mes'];
		$fecha_conciliacion = $this->data['conciliacion']['fecha_conciliacion'];


				if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}

				switch($mes_mov){
					case '1': $ano_anterior=$ano_movimiento-1; $fecha_mes_anterior = "31/12/".$ano_anterior; $fecha_inicio="01/01/".$ano_movimiento; $fecha_final="31/01/".$ano_movimiento;  break;
					case '2': $fecha_mes_anterior = "31/01/".$ano_movimiento; $fecha_inicio="01/02/".$ano_movimiento; $fecha_final=$dia_feb."/02/".$ano_movimiento;  break;
					case '3': $fecha_mes_anterior = $dia_feb."/02/".$ano_movimiento; $fecha_inicio="01/03/".$ano_movimiento; $fecha_final="31/03/".$ano_movimiento;  break;
					case '4': $fecha_mes_anterior = "31/03/".$ano_movimiento; $fecha_inicio="01/04/".$ano_movimiento; $fecha_final="30/04/".$ano_movimiento;  break;
					case '5': $fecha_mes_anterior = "30/04/".$ano_movimiento; $fecha_inicio="01/05/".$ano_movimiento; $fecha_final="31/05/".$ano_movimiento;  break;
					case '6': $fecha_mes_anterior = "31/05/".$ano_movimiento; $fecha_inicio="01/06/".$ano_movimiento; $fecha_final="30/06/".$ano_movimiento;  break;
					case '7': $fecha_mes_anterior = "30/06/".$ano_movimiento; $fecha_inicio="01/07/".$ano_movimiento; $fecha_final="31/07/".$ano_movimiento;  break;
					case '8': $fecha_mes_anterior = "31/07/".$ano_movimiento; $fecha_inicio="01/08/".$ano_movimiento; $fecha_final="31/08/".$ano_movimiento;  break;
					case '9': $fecha_mes_anterior = "31/08/".$ano_movimiento; $fecha_inicio="01/09/".$ano_movimiento; $fecha_final="30/09/".$ano_movimiento;  break;
					case '10': $fecha_mes_anterior = "30/09/".$ano_movimiento; $fecha_inicio="01/10/".$ano_movimiento; $fecha_final="31/10/".$ano_movimiento;  break;
					case '11': $fecha_mes_anterior = "31/10/".$ano_movimiento; $fecha_inicio="01/11/".$ano_movimiento; $fecha_final="30/11/".$ano_movimiento;  break;
					case '12': $fecha_mes_anterior = "30/11/".$ano_movimiento; $fecha_inicio="01/12/".$ano_movimiento; $fecha_final="31/12/".$ano_movimiento;  break;
				}

// SALDO MES ANTERIOR TESORERÍA

	$condicion = $this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_banc'";
	$sql_suma_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_mes_anterior' AND tipo=1)";
	$sql_resta_anterior_activos = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_mes_anterior' AND tipo=1)";
	$sql_suma_anterior_anul     = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_mes_anterior' AND tipo=2)";
	$sql_resta_anterior_anul    = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_mes_anterior' AND tipo=2)";

	$suma_anterior_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
	$resta_anterior_activos = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
	$suma_anterior_anul     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul);
	$resta_anterior_anul    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul);

	$suma_anterior_activos[0][0]['monto'];
	$resta_anterior_activos[0][0]['monto'];
	$suma_anterior_anul[0][0]['monto'];
	$resta_anterior_anul[0][0]['monto'];
	$total_anterior_tesoreria = (($suma_anterior_activos[0][0]['monto'] - $resta_anterior_activos[0][0]['monto']) + $resta_anterior_anul[0][0]['monto']) - $suma_anterior_anul[0][0]['monto'];
	$this->set('total_anterior_tesoreria', $total_anterior_tesoreria);

	// MOVIMIENTO MENSUAL TESORERÍA

	$condicion = $this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_banc'";
	$sql_suma_anterior_activos_dp  = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND tipo_documento=1 AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND tipo=1)";
	$sql_suma_anterior_activos_nc  = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND tipo_documento=2 AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND tipo=1)";
	$sql_resta_anterior_activos_nd = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND tipo_documento=3 AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND tipo=1)";
	$sql_resta_anterior_activos_ch = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND tipo_documento=4 AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND tipo=1)";
	$sql_suma_anterior_anul_dp     = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND tipo_documento=1 AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND tipo=2)";
	$sql_suma_anterior_anul_nc     = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND tipo_documento=2 AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND tipo=2)";
	$sql_resta_anterior_anul_nd    = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND tipo_documento=3 AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND tipo=2)";
	$sql_resta_anterior_anul_ch    = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND tipo_documento=4 AND (fecha BETWEEN '$fecha_inicio' AND '$fecha_final' AND tipo=2)";

	$suma_anterior_activos_dp  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos_dp);
	$suma_anterior_activos_nc  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos_nc);
	$resta_anterior_activos_nd = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos_nd);
	$resta_anterior_activos_ch = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos_ch);
	$suma_anterior_anul_dp     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul_dp);
	$suma_anterior_anul_nc     = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anul_nc);
	$resta_anterior_anul_nd    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul_nd);
	$resta_anterior_anul_ch    = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anul_ch);

    $suma_dep = ($suma_anterior_activos_dp[0][0]['monto']-$suma_anterior_anul_dp[0][0]['monto']);
    $suma_nc  = ($suma_anterior_activos_nc[0][0]['monto']-$suma_anterior_anul_nc[0][0]['monto']);
    $suma_nd  = ($resta_anterior_activos_nd[0][0]['monto']-$resta_anterior_anul_nd[0][0]['monto']);
    $suma_che = ($resta_anterior_activos_ch[0][0]['monto']-$resta_anterior_anul_ch[0][0]['monto']);

    $this->set('suma_dep', $suma_dep);
    $this->set('suma_nc', $suma_nc);
    $this->set('suma_nd', $suma_nd);
    $this->set('suma_che', $suma_che);


// SALDO MES ANTERIOR BANCO

	$suma_dep_nc = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as monto FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$cod_entidad' AND cod_sucursal='$cod_sucursal' AND cuenta_bancaria='$cuenta_banc' AND (tipo_documento='1' OR  tipo_documento='2') AND (a.fecha_documento BETWEEN '01/01/2000' AND '$fecha_mes_anterior')");
	$suma_che_nd = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as monto FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$cod_entidad' AND cod_sucursal='$cod_sucursal' AND cuenta_bancaria='$cuenta_banc' AND (tipo_documento='3' OR  tipo_documento='4') AND (a.fecha_documento BETWEEN '01/01/2000' AND '$fecha_mes_anterior')");
    $total_anterior_banco = ($suma_dep_nc[0][0]['monto'] - $suma_che_nd[0][0]['monto']);
    $this->set('total_anterior_banco', $total_anterior_banco);


		// DATOS REPORTE EN PARTE: A

		$conc_banc_a = $this->v_cstd05_estado_cuenta_vs_tesoreria->findAll($this->SQLCA()." and cod_entidad_bancaria=$cod_entidad and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_banc' and ano_movimiento=$ano_movimiento and mes=$mes_mov");
		if(!empty($conc_banc_a))
			$this->set('conc_banc_a', $conc_banc_a);
		else
			$this->set('conc_banc_a', array());



		// DATOS REPORTE EN PARTE: B

		$conc_banc_b = $this->v_cstd05_estado_cuenta_no_tesoreria->findAll($this->SQLCA()." and cod_entidad_bancaria=$cod_entidad and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_banc' and (ano_movimiento <= $ano_movimiento and mes <= $mes_mov)");
		if(!empty($conc_banc_b))
			$this->set('conc_banc_b', $conc_banc_b);
		else
			$this->set('conc_banc_b', array());



		// DATOS REPORTE EN PARTE: C

		$conc_banc_c = $this->v_cstd05_tesoreria_no_estado_cuenta->findAll($this->SQLCA()." and cod_entidad_bancaria=$cod_entidad and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_banc' and ano_movimiento <= $ano_movimiento and mes <= $mes_mov");
		if(!empty($conc_banc_c))
			$this->set('conc_banc_c', $conc_banc_c);
		else
			$this->set('conc_banc_c', array());
	}
}




function conciliacion_bancaria_metodo_comparativo_tvsb ($var=null){
	set_time_limit(0);
	ini_set("memory_limit", "2048M");
	if($var=='si'){
		$this->layout ="ajax";
		$this->set('ir', $var);
		$entidades = $this->v_cstd05_tesoreria_vs_estado_cuenta->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd05_tesoreria_vs_estado_cuenta.cod_entidad_bancaria', '{n}.v_cstd05_tesoreria_vs_estado_cuenta.banco');
		$this->concatena_cuatro_digitos($entidades, 'entidades');
	}


	else if($var=='pdf'){
		$this->layout = "pdf";
		$cod_entidad = $this->data['conciliacion']['entidad'];
		$cod_sucursal = $this->data['conciliacion']['sucursal'];
		$cuenta_banc = $this->data['conciliacion']['cuenta'];
		$ano_movimiento = $this->data['conciliacion']['anos'];
		$mes_mov = $this->data['conciliacion']['mes'];
		$fecha_conciliacion = $this->data['conciliacion']['fecha_conciliacion'];


				if($this->anio_bisiesto($ano_movimiento)==true){$dia_feb="29";}else{$dia_feb="28";}
				switch($mes_mov){
					case '1': $ano_anterior=$ano_movimiento-1; $fecha_mes_anterior = "31/12/".$ano_anterior; $fecha_inicio="01/01/".$ano_movimiento; $fecha_final="31/01/".$ano_movimiento;  break;
					case '2': $fecha_mes_anterior = "31/01/".$ano_movimiento; $fecha_inicio="01/02/".$ano_movimiento; $fecha_final=$dia_feb."/02/".$ano_movimiento;  break;
					case '3': $fecha_mes_anterior = $dia_feb."/02/".$ano_movimiento; $fecha_inicio="01/03/".$ano_movimiento; $fecha_final="31/03/".$ano_movimiento;  break;
					case '4': $fecha_mes_anterior = "31/03/".$ano_movimiento; $fecha_inicio="01/04/".$ano_movimiento; $fecha_final="30/04/".$ano_movimiento;  break;
					case '5': $fecha_mes_anterior = "30/04/".$ano_movimiento; $fecha_inicio="01/05/".$ano_movimiento; $fecha_final="31/05/".$ano_movimiento;  break;
					case '6': $fecha_mes_anterior = "31/05/".$ano_movimiento; $fecha_inicio="01/06/".$ano_movimiento; $fecha_final="30/06/".$ano_movimiento;  break;
					case '7': $fecha_mes_anterior = "30/06/".$ano_movimiento; $fecha_inicio="01/07/".$ano_movimiento; $fecha_final="31/07/".$ano_movimiento;  break;
					case '8': $fecha_mes_anterior = "31/07/".$ano_movimiento; $fecha_inicio="01/08/".$ano_movimiento; $fecha_final="31/08/".$ano_movimiento;  break;
					case '9': $fecha_mes_anterior = "31/08/".$ano_movimiento; $fecha_inicio="01/09/".$ano_movimiento; $fecha_final="30/09/".$ano_movimiento;  break;
					case '10': $fecha_mes_anterior = "30/09/".$ano_movimiento; $fecha_inicio="01/10/".$ano_movimiento; $fecha_final="31/10/".$ano_movimiento;  break;
					case '11': $fecha_mes_anterior = "31/10/".$ano_movimiento; $fecha_inicio="01/11/".$ano_movimiento; $fecha_final="30/11/".$ano_movimiento;  break;
					case '12': $fecha_mes_anterior = "30/11/".$ano_movimiento; $fecha_inicio="01/12/".$ano_movimiento; $fecha_final="31/12/".$ano_movimiento;  break;
				}

// SALDO MES ANTERIOR TESORERÍA

	$condicion = $this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_banc'";
	$sql_suma_anterior_activos   = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_mes_anterior') AND tipo=1";
	$sql_resta_anterior_activos  = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_mes_anterior') AND tipo=1";
	$sql_suma_anterior_anulados  = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND (tipo_documento=1 OR tipo_documento=2) AND (fecha BETWEEN '01/01/2000' AND '$fecha_mes_anterior') AND tipo=2";
	$sql_resta_anterior_anulados = "SELECT Sum(monto) as monto FROM v_cstd_mov_general WHERE ".$condicion." AND (tipo_documento=3 OR tipo_documento=4) AND (fecha BETWEEN '01/01/2000' AND '$fecha_mes_anterior') AND tipo=2";

	$depo_nota_cred_activos  = $this->v_cstd_mov_gral->execute($sql_suma_anterior_activos);
	$cheq_nota_debi_activos  = $this->v_cstd_mov_gral->execute($sql_resta_anterior_activos);
	$depo_nota_cred_anulados = $this->v_cstd_mov_gral->execute($sql_suma_anterior_anulados);
	$cheq_nota_debi_anulados = $this->v_cstd_mov_gral->execute($sql_resta_anterior_anulados);

    $total_anterior_tesoreria = (($depo_nota_cred_activos[0][0]['monto'] + $cheq_nota_debi_anulados[0][0]['monto']) - ($cheq_nota_debi_activos[0][0]['monto'] + $depo_nota_cred_anulados[0][0]['monto']));

	$this->set('total_anterior_tesoreria', $total_anterior_tesoreria);

// SALDO MES ANTERIOR BANCO

	$suma_dep_nc = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as monto FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$cod_entidad' AND cod_sucursal='$cod_sucursal' AND cuenta_bancaria='$cuenta_banc' AND (tipo_documento='1' OR  tipo_documento='2') AND (a.fecha_documento BETWEEN '01/01/2000' AND '$fecha_mes_anterior')");
	$suma_che_nd  = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as monto FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$cod_entidad' AND cod_sucursal='$cod_sucursal' AND cuenta_bancaria='$cuenta_banc' AND (tipo_documento='3' OR  tipo_documento='4') AND (a.fecha_documento BETWEEN '01/01/2000' AND '$fecha_mes_anterior')");
    $total_anterior_banco = ($suma_dep_nc[0][0]['monto'] - $suma_che_nd[0][0]['monto']);
    $this->set('total_anterior_banco', $total_anterior_banco);

    // MOVIMIENTOS MES ACTUAL

	$suma_dep = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as monto FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$cod_entidad' AND cod_sucursal='$cod_sucursal' AND cuenta_bancaria='$cuenta_banc' AND tipo_documento=1 AND (a.fecha_documento BETWEEN '$fecha_inicio' AND '$fecha_final')");
	$suma_nc  = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as monto FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$cod_entidad' AND cod_sucursal='$cod_sucursal' AND cuenta_bancaria='$cuenta_banc' AND tipo_documento=2 AND (a.fecha_documento BETWEEN '$fecha_inicio' AND '$fecha_final')");
	$suma_nd  = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as monto FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$cod_entidad' AND cod_sucursal='$cod_sucursal' AND cuenta_bancaria='$cuenta_banc' AND tipo_documento=3 AND (a.fecha_documento BETWEEN '$fecha_inicio' AND '$fecha_final')");
	$suma_che = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as monto FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$cod_entidad' AND cod_sucursal='$cod_sucursal' AND cuenta_bancaria='$cuenta_banc 'AND tipo_documento=4 AND (a.fecha_documento BETWEEN '$fecha_inicio' AND '$fecha_final')");

    $suma_dep = $suma_dep[0][0]['monto'];
    $suma_nc = $suma_nc[0][0]['monto'];
    $suma_nd = $suma_nd[0][0]['monto'];
    $suma_che = $suma_che[0][0]['monto'];

    $this->set('suma_dep', $suma_dep);
    $this->set('suma_nc', $suma_nc);
    $this->set('suma_nd', $suma_nd);
    $this->set('suma_che', $suma_che);


		// DATOS REPORTE EN PARTE: A

		$conc_banc_a = $this->v_cstd05_tesoreria_vs_estado_cuenta->findAll($this->SQLCA()." and cod_entidad_bancaria=$cod_entidad and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_banc' and ano_movimiento=$ano_movimiento and mes=$mes_mov");
		if(!empty($conc_banc_a))
			$this->set('conc_banc_a', $conc_banc_a);
		else
			$this->set('conc_banc_a', array());



		// DATOS REPORTE EN PARTE: B

		$conc_banc_b = $this->v_cstd05_estado_cuenta_no_tesoreria->findAll($this->SQLCA()." and cod_entidad_bancaria=$cod_entidad and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_banc' and ano_movimiento <= $ano_movimiento and mes <= $mes_mov");
		if(!empty($conc_banc_b))
			$this->set('conc_banc_b', $conc_banc_b);
		else
			$this->set('conc_banc_b', array());



		// DATOS REPORTE EN PARTE: C

		$conc_banc_c = $this->v_cstd05_tesoreria_no_estado_cuenta->findAll($this->SQLCA()." and cod_entidad_bancaria=$cod_entidad and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_banc' and (ano_movimiento <= $ano_movimiento and mes <= $mes_mov)");
		if(!empty($conc_banc_c))
			$this->set('conc_banc_c', $conc_banc_c);
		else
			$this->set('conc_banc_c', array());
	}
}





function conciliacion_ss_accion ($var1 = null, $var_entidad = null, $var_sucursal = null, $cuenta = null, $anom = null){
	$this->layout ="ajax";
	switch((int)$var1){
		case 1: // SUCURSALES
				$this->set('var_entidad', $var_entidad);
				$entidad = $this->v_cstd05_estado_cuenta_vs_tesoreria->execute("SELECT cod_entidad_bancaria, banco FROM v_cstd05_estado_cuenta_vs_tesoreria WHERE ".$this->SQLCA()." and cod_entidad_bancaria=$var_entidad LIMIT 1;");
				$dseleccion = $this->v_cstd05_estado_cuenta_vs_tesoreria->generateList($this->SQLCA()." and cod_entidad_bancaria=$var_entidad", 'cod_sucursal ASC', null, '{n}.v_cstd05_estado_cuenta_vs_tesoreria.cod_sucursal', '{n}.v_cstd05_estado_cuenta_vs_tesoreria.sucursal');
				echo "<script>
						document.getElementById('cod_entidad').value='". mascara($entidad[0][0]['cod_entidad_bancaria'], 4) ."';
						document.getElementById('deno_entidad').value='".$entidad[0][0]['banco']."';
						document.getElementById('cod_sucursal').value='';
						document.getElementById('deno_sucursal').value='';
						document.getElementById('select_cuentabanc').innerHTML='<select name=\'data[conciliacion][cuenta]\' id=\'select3\'></select>';
						document.getElementById('select_anosm').innerHTML='<select name=\'data[conciliacion][anos]\' id=\'select4\'></select>';
						document.getElementById('select_mesm').innerHTML='<select name=\'data[conciliacion][mes]\' id=\'select5\'></select>';
						</script>";
				break;
		case 2: // CUENTAS BANCARIAS
				$this->set('var_entidad', $var_entidad);
				$this->set('var_sucursal', $var_sucursal);
				$sucursal = $this->v_cstd05_estado_cuenta_vs_tesoreria->execute("SELECT cod_sucursal, sucursal FROM v_cstd05_estado_cuenta_vs_tesoreria WHERE ".$this->SQLCA()." and cod_entidad_bancaria=$var_entidad and cod_sucursal=$var_sucursal LIMIT 1;");
				$dseleccion = $this->v_cstd05_estado_cuenta_vs_tesoreria->generateList($this->SQLCA()." and cod_entidad_bancaria=$var_entidad and cod_sucursal=$var_sucursal", 'cuenta_bancaria ASC', null, '{n}.v_cstd05_estado_cuenta_vs_tesoreria.cuenta_bancaria', '{n}.v_cstd05_estado_cuenta_vs_tesoreria.cuenta_bancaria');
				echo "<script>
						document.getElementById('cod_sucursal').value='". mascara($sucursal[0][0]['cod_sucursal'], 4) ."';
						document.getElementById('deno_sucursal').value='".$sucursal[0][0]['sucursal']."';
						document.getElementById('select_anosm').innerHTML='<select name=\'data[conciliacion][anos]\' id=\'select4\'></select>';
						document.getElementById('select_mesm').innerHTML='<select name=\'data[conciliacion][mes]\' id=\'select5\'></select>';
						</script>";
				break;
		case 3: // ANOS
				$this->set('var_entidad', $var_entidad);
				$this->set('var_sucursal', $var_sucursal);
				$this->set('cuenta', $cuenta);
				$dseleccion = $this->v_cstd05_estado_cuenta_vs_tesoreria->generateList($this->SQLCA()." and cod_entidad_bancaria=$var_entidad and cod_sucursal=$var_sucursal and cuenta_bancaria='$cuenta'", 'ano_movimiento ASC', null, '{n}.v_cstd05_estado_cuenta_vs_tesoreria.ano_movimiento', '{n}.v_cstd05_estado_cuenta_vs_tesoreria.ano_movimiento');
				echo "<script>
						document.getElementById('select_mesm').innerHTML='<select name=\'data[conciliacion][mes]\' id=\'select5\'></select>';
						</script>";
				break;
		case 4: // MESES
				/* $this->set('var_entidad', $var_entidad);
				$this->set('var_sucursal', $var_sucursal);
				$this->set('cuenta', $cuenta);
				$this->set('anom', $anom); */
				$dseleccion = $this->v_cstd05_estado_cuenta_vs_tesoreria->generateList($this->SQLCA()." and cod_entidad_bancaria=$var_entidad and cod_sucursal=$var_sucursal and cuenta_bancaria='$cuenta' and ano_movimiento=$anom", 'mes ASC', null, '{n}.v_cstd05_estado_cuenta_vs_tesoreria.mes', '{n}.v_cstd05_estado_cuenta_vs_tesoreria.deno_mes');
				break;
		default:
				$dseleccion = array();
				break;
	}

	$this->set('accion', $var1);

	if(!empty($dseleccion)){
		if((int)$var1==1)
			$this->concatena_cuatro_digitos($dseleccion, 'dseleccion');
		else
			$this->set('dseleccion', $dseleccion);
	}else{
		$this->set('dseleccion', array());
	}
}


function conciliacion_ss_accion_tvsb ($var1 = null, $var_entidad = null, $var_sucursal = null, $cuenta = null, $anom = null){
	$this->layout ="ajax";
	switch((int)$var1){
		case 1: // SUCURSALES
				$this->set('var_entidad', $var_entidad);
				$entidad = $this->v_cstd05_tesoreria_vs_estado_cuenta->execute("SELECT cod_entidad_bancaria, banco FROM v_cstd05_tesoreria_vs_estado_cuenta WHERE ".$this->SQLCA()." and cod_entidad_bancaria=$var_entidad LIMIT 1;");
				$dseleccion = $this->v_cstd05_tesoreria_vs_estado_cuenta->generateList($this->SQLCA()." and cod_entidad_bancaria=$var_entidad", 'cod_sucursal ASC', null, '{n}.v_cstd05_tesoreria_vs_estado_cuenta.cod_sucursal', '{n}.v_cstd05_tesoreria_vs_estado_cuenta.sucursal');
				echo "<script>
						document.getElementById('cod_entidad').value='". mascara($entidad[0][0]['cod_entidad_bancaria'], 4) ."';
						document.getElementById('deno_entidad').value='".$entidad[0][0]['banco']."';
						document.getElementById('cod_sucursal').value='';
						document.getElementById('deno_sucursal').value='';
						document.getElementById('select_cuentabanc').innerHTML='<select name=\'data[conciliacion][cuenta]\' id=\'select3\'></select>';
						document.getElementById('select_anosm').innerHTML='<select name=\'data[conciliacion][anos]\' id=\'select4\'></select>';
						document.getElementById('select_mesm').innerHTML='<select name=\'data[conciliacion][mes]\' id=\'select5\'></select>';
						</script>";
				break;
		case 2: // CUENTAS BANCARIAS
				$this->set('var_entidad', $var_entidad);
				$this->set('var_sucursal', $var_sucursal);
				$sucursal = $this->v_cstd05_tesoreria_vs_estado_cuenta->execute("SELECT cod_sucursal, sucursal FROM v_cstd05_tesoreria_vs_estado_cuenta WHERE ".$this->SQLCA()." and cod_entidad_bancaria=$var_entidad and cod_sucursal=$var_sucursal LIMIT 1;");
				$dseleccion = $this->v_cstd05_tesoreria_vs_estado_cuenta->generateList($this->SQLCA()." and cod_entidad_bancaria=$var_entidad and cod_sucursal=$var_sucursal", 'cuenta_bancaria ASC', null, '{n}.v_cstd05_tesoreria_vs_estado_cuenta.cuenta_bancaria', '{n}.v_cstd05_tesoreria_vs_estado_cuenta.cuenta_bancaria');
				echo "<script>
						document.getElementById('cod_sucursal').value='". mascara($sucursal[0][0]['cod_sucursal'], 4) ."';
						document.getElementById('deno_sucursal').value='".$sucursal[0][0]['sucursal']."';
						document.getElementById('select_anosm').innerHTML='<select name=\'data[conciliacion][anos]\' id=\'select4\'></select>';
						document.getElementById('select_mesm').innerHTML='<select name=\'data[conciliacion][mes]\' id=\'select5\'></select>';
						</script>";
				break;
		case 3: // ANOS
				$this->set('var_entidad', $var_entidad);
				$this->set('var_sucursal', $var_sucursal);
				$this->set('cuenta', $cuenta);
				$dseleccion = $this->v_cstd05_tesoreria_vs_estado_cuenta->generateList($this->SQLCA()." and cod_entidad_bancaria=$var_entidad and cod_sucursal=$var_sucursal and cuenta_bancaria='$cuenta'", 'ano_movimiento ASC', null, '{n}.v_cstd05_tesoreria_vs_estado_cuenta.ano_movimiento', '{n}.v_cstd05_tesoreria_vs_estado_cuenta.ano_movimiento');
				echo "<script>
						document.getElementById('select_mesm').innerHTML='<select name=\'data[conciliacion][mes]\' id=\'select5\'></select>';
						</script>";
				break;
		case 4: // MESES
				/* $this->set('var_entidad', $var_entidad);
				$this->set('var_sucursal', $var_sucursal);
				$this->set('cuenta', $cuenta);
				$this->set('anom', $anom); */
				$dseleccion = $this->v_cstd05_tesoreria_vs_estado_cuenta->generateList($this->SQLCA()." and cod_entidad_bancaria=$var_entidad and cod_sucursal=$var_sucursal and cuenta_bancaria='$cuenta' and ano_movimiento=$anom", 'mes ASC', null, '{n}.v_cstd05_tesoreria_vs_estado_cuenta.mes', '{n}.v_cstd05_tesoreria_vs_estado_cuenta.deno_mes');
				break;
		default:
				$dseleccion = array();
				break;
	}

	$this->set('accion', $var1);

	if(!empty($dseleccion)){
		if((int)$var1==1)
			$this->concatena_cuatro_digitos($dseleccion, 'dseleccion');
		else
			$this->set('dseleccion', $dseleccion);
	}else{
		$this->set('dseleccion', array());
	}
}

}//fin clase
?>
