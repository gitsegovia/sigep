<?php
/*
 * Creado el 02/05/2008 a las 01:02:38 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
class Cstp05EstadoCuentasController extends AppController {
 	var $name = 'cstp05_estado_cuentas';
 	var $uses = array ('v_cstd01_bancos','v_cstd01_sucursales','cstd05_estado_cuentas', 'cstd03_cheque_cuerpo', 'cstd03_movimientos_manuales', 'cstd04_movimientos_generales', 'cstd02_cuentas_bancarias', 'cstd01_entidades_bancarias', 'cstd01_sucursales_bancarias', 'ccfd03_instalacion', 'cstd03_cheque_numero', 'cugd03_acta_anulacion_numero', 'cugd03_acta_anulacion_cuerpo', 'ccfd04_cierre_mes', 'cstd04_cheque_poremitir', 'cstd06_comprobante_numero_egreso', 'cstd06_comprobante_cuerpo_egreso', 'csrd01_solicitud_recurso_cuerpo', 'cugd02_dependencia', 'cstd09_notadebito_cuerpo','v_cstd_mov_gral','v_vistas_cheques_union');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');


function beforeFilter(){
	$this->checkSession();
}

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession

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

function ano($var=null){
	$this->layout="ajax";
	$ano = (int) $var;
	$this->Session->write('c_ano', $ano);
	$this->set('mensaje','EL AÑO FUE CAMBIADO, PROCEDA A SELECCIONAR LA CUENTA BANCARIA');
}

function mes($var=null){
	$this->layout="ajax";
	$mes = (int) $var;
	$this->Session->write('c_mes', $mes);
	$this->set('mensaje','EL MES FUE CAMBIADO, PROCEDA A SELECCIONAR LA CUENTA BANCARIA');
	echo'<script>';
  	  echo"document.getElementById('cuenta_bancaria').value='';";
  	  echo"document.getElementById('select_3').value='';";
  	echo'</script>';
}

function vacio($var=null){
	$this->layout="ajax";
}












function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zeros($x).' - '.$y;

		}
		$this->set($nomVar, $cod);
	}
}



function zeros($x=null){
	if($x != null){
		if($x<10){
			$x="000".$x;
		}else if($x>=10 && $x<=99){
			$x="00".$x;
		}else if($x>=100 && $x<=999){
			$x="0".$x;
		}
	}
	return $x;

}






function index () {
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
 	$this->set('vector_cuenta','');
 	$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'direccion_superior');

    $ano = $this->ano_ejecucion();
	$mes = (int) date('m');
	$this->set('ano_movimiento', $ano);

    $this->Session->delete('c_cuenta');
    $this->Session->delete('c_sucursal');
    $this->Session->delete('c_cuenta_ban');
    $this->Session->delete('c_ano');
    $this->Session->delete('c_mes');

    $this->Session->write('c_ano', $ano);
    $this->Session->write('c_mes', $mes);
}



function select3($select=null,$var=null) {
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
		  $this->set('SELECT','cuenta');//El parametro que se le pasa para que busque el proximo select (cuando entre en select3)
		  $this->set('codigo','sucursal');//El nombre que se le asigna al select actual cuando se crea
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('c_cuenta', $var);
		  $cond ="cod_entidad_bancaria=".$var;
		  $busca=$this->SQLCA()." and cod_entidad_bancaria=".$var;
		  $this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'vector');
		break;
		case 'cuenta':
		  $this->set('SELECT','otro');
		  $this->set('codigo','cuenta');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $this->set('no','no');
		  $this->Session->write('c_sucursal', $var);
		  if($var!='no'){
			  $cond = $this->SQLCA()." and cod_entidad_bancaria =".$this->Session->read('c_cuenta')." and cod_sucursal=".$var;
	    	  $lista = $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
			  if($lista == 0){
		         $this->set('vector',array('no'=>'no hay registros'));
			  }else{
		    	 $this->set('vector',$lista);
		    	 //$this->concatena($lista, 'vector');
			  }
		  }else{
		  	$this->set('vector',array('no'=>''));
		  }
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
}//select3




function mostrar4($select=null,$var=null) {
	$this->layout = "ajax";

    if($var!=null && $var!='no'){
	switch($select){
		case 'entidad_bancaria':
		    $cond ="cod_entidad_bancaria=".$var;
		    $a=  $this->cstd01_entidades_bancarias->findAll($cond);
            echo "<input type='text' name='data[cstp05_estado_cuentas][cod_entidad_bancaria]' value='".$this->mascara3($a[0]['cstd01_entidades_bancarias']['cod_entidad_bancaria'])."' id='cod_entidad_bancaria' style='text-align:center' class='inputtext' />";
		break;
		case 'sucursal':
		    $cond ="cod_entidad_bancaria=".$this->Session->read('c_cuenta')." and cod_sucursal=".$var;
		    $a=  $this->cstd01_sucursales_bancarias->findAll($cond);
            echo "<input type='text' name='data[cstp05_estado_cuentas][cod_sucursal_bancaria]' value='".$this->mascara3($a[0]['cstd01_sucursales_bancarias']['cod_sucursal'])."' id='cod_sucursal_bancaria' style='text-align:center' class='inputtext' />";
		    break;
		case 'cuenta':
 			$cond = $this->SQLCA()." and cod_entidad_bancaria=".$this->Session->read('c_cuenta')." and cod_sucursal=".$this->Session->read('c_sucursal')." and cuenta_bancaria='".$var."'";
 			$concepto = $this->cstd02_cuentas_bancarias->findAll($cond,array('concepto_manejo'),null,1,1,null);
			echo "<input type='text' name='data[cstp05_estado_cuentas][cuenta_bancaria]' value='".$concepto[0]['cstd02_cuentas_bancarias']['concepto_manejo']."' id='cuenta_bancaria' class='inputtext' />";
		break;
	  }//fin switch
	}else{
		echo "<input type='text' name='data[cstp05_estado_cuentas]' id='cod_entidad_bancaria_input' style='text-align:center' class='inputtext' />";
	}
}//mostrar4





function mostrar3($select=null,$var=null) {
	$this->layout = "ajax";

	if($var!=null && $var!='no'){
	switch($select){
		case 'entidad_bancaria':
		  $cond ="cod_entidad_bancaria=".$var;
		  $a=  $this->cstd01_entidades_bancarias->findAll($cond);
          echo "<input type='text' name='data[cstp05_estado_cuentas][deno_entidad_bancaria]' value='".$a[0]['cstd01_entidades_bancarias']['denominacion']."' id='deno_entidad_bancaria' class='inputtext' />";
		break;
		case 'sucursal':
		    $cond ="cod_entidad_bancaria=".$this->Session->read('c_cuenta')." and cod_sucursal=".$var;
		    $a=  $this->cstd01_sucursales_bancarias->findAll($cond);
            echo "<input type='text' name='data[cstp05_estado_cuentas][deno_sucursal_bancaria]' value='".$a[0]['cstd01_sucursales_bancarias']['denominacion']."' id='deno_sucursal_bancaria' class='inputtext' />";
		   break;
		case 'cuenta':
		//$cond = $this->SQLCA()." and cod_entidad_bancaria=".$this->Session->read('c_cuenta')." and cod_sucursal=".$this->Session->read('c_sucursal')." and cuenta_bancaria=".$var;
		//$datos = $this->cstd02_cuentas_bancarias->findAll($cond,null,'cod_entidad_bancaria, cod_sucursal ASC');
		//echo "<input type='text' name='data[cstp05_estado_cuentas][cod_sucursal_bancaria]' value='".$datos[0]['cstd02_cuentas_bancarias']['concepto_manejo']."' id='cod_sucursal_bancaria' class='inputtext' />";
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
		echo "<input type='text' name='data[cstp05_estado_cuentas]' id='cod_entidad_bancaria' style='text-align:center' class='inputtext' />";
	}
}//mostrar3




function mostrar5($select=null,$var=null) {
	$this->layout = "ajax";
    if($var!=null && $var!='no'){
    	$entidad  = $this->Session->read('c_cuenta');
    	$sucursal = $this->Session->read('c_sucursal');
    	$cuenta   = $var;
    	$ano_mov  = $this->Session->read('c_ano');
    	$mes_mov  = $this->Session->read('c_mes');
    	$this->Session->write('c_cuenta_ban', $var);

    	if($this->anio_bisiesto($ano_mov)==true){$dia_feb="29";}else{$dia_feb="28";}

    	switch($mes_mov){
    		case '1': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '2': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = $dia_feb."/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '3': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '4': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "30/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '5': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '6': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "30/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '7': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '8': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '9': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "30/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '10': $fecha_inicial = "01/".$mes_mov."/$ano_mov"; $fecha_final = "31/".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '11': $fecha_inicial = "01/".$mes_mov."/$ano_mov"; $fecha_final = "30/".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '12': $fecha_inicial = "01/".$mes_mov."/$ano_mov"; $fecha_final = "31/".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    	}

		/*

		MODIFICACION DONDE SOLO SE LE QUITO EL ANO DE MOVIMIENTO Y AGREGO COMPARACIÓN DEL MONTO EN EL FILTRO.

    	$sql = "select a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.tipo_documento, a.numero_documento, a.fecha_documento, a.monto,
    			(select b.numero_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento = b.numero_documento)::int4 as numero_tesoreria,
    			(select b.fecha_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento	= b.numero_documento)::date as fecha_tesoreria,
    			(select b.monto from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento = b.numero_documento)::numeric as monto_tesoreria
				from cstd05_estado_cuentas a
				where
				a.cod_presi 		= ".$this->Session->read('SScodpresi')."    and
				a.cod_entidad		= ".$this->Session->read('SScodentidad')."  and
				a.cod_tipo_inst 	= ".$this->Session->read('SScodtipoinst')." and
				a.cod_inst		    = ".$this->Session->read('SScodinst')."     and
				a.cod_dep		    = ".$this->Session->read('SScoddep')."      and
				a.ano_movimiento	    = ".$ano_mov."  and
				a.cod_entidad_bancaria	= ".$entidad."  and
				a.cod_sucursal		    = ".$sucursal." and
				a.cuenta_bancaria		= '$cuenta' and a.fecha_documento BETWEEN '$fecha_inicial' AND '$fecha_final'
				order By a.tipo_documento, a.numero_documento, a.fecha_documento;";
		*/

		$sql = "select a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.tipo_documento, a.numero_documento, a.fecha_documento, a.monto,
    			(select b.numero_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento = b.numero_documento)::int4 as numero_tesoreria,
    			(select b.fecha_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento = b.numero_documento)::date as fecha_tesoreria,
    			(select b.monto from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento = b.numero_documento)::numeric as monto_tesoreria
				from cstd05_estado_cuentas a
				where
				a.cod_presi 		= ".$this->Session->read('SScodpresi')."    and
				a.cod_entidad		= ".$this->Session->read('SScodentidad')."  and
				a.cod_tipo_inst 	= ".$this->Session->read('SScodtipoinst')." and
				a.cod_inst		    = ".$this->Session->read('SScodinst')."     and
				a.cod_dep		    = ".$this->Session->read('SScoddep')."      and
				a.cod_entidad_bancaria	= ".$entidad."  and
				a.cod_sucursal		    = ".$sucursal." and
				a.cuenta_bancaria		= '$cuenta' and a.fecha_documento BETWEEN '$fecha_inicial' AND '$fecha_final'
				order By a.fecha_documento, a.tipo_documento, a.numero_documento;";

		$datos = $this->cstd05_estado_cuentas->execute($sql);

		$suma_dep_nc = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as suma_dep_nc FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$entidad' AND cod_sucursal='$sucursal' AND cuenta_bancaria='$cuenta' AND (tipo_documento='1' OR  tipo_documento='2') AND a.fecha_documento BETWEEN '01/01/1900' AND '$fecha_anterior'");
		$suma_che_nd = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as suma_che_nd FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$entidad' AND cod_sucursal='$sucursal' AND cuenta_bancaria='$cuenta' AND (tipo_documento='3' OR  tipo_documento='4') AND a.fecha_documento BETWEEN '01/01/1900' AND '$fecha_anterior'");
		$this->set('suma_dep_nc', isset($suma_dep_nc[0][0]['suma_dep_nc']) ? $suma_dep_nc[0][0]['suma_dep_nc'] : 0);
		$this->set('suma_che_nd', isset($suma_che_nd[0][0]['suma_che_nd']) ? $suma_che_nd[0][0]['suma_che_nd'] : 0);

		if($datos!=null){
			$this->set('datos',$datos);
			$this->set('error',0);
		}else{
			$this->set('datos',null);
			$this->set('error',0);
		}

    }else{
 	$this->set('datos',null);
 	$this->set('error',1);
    }
}



function tipodocumento($var=null){
	$this->layout = "ajax";
	$this->set('tipo',$var);
}


function anio_bisiesto($anyo){
	if(!checkdate(02,29,$anyo)){
		return false;
	}else{
		return true;
	}
}


function buscar_numerodoc($tipo_doc=null, $num_doc=null){
	$this->layout = "ajax";

  	$entidad  = $this->Session->read('c_cuenta');
	$sucursal = $this->Session->read('c_sucursal');
	$cuenta   = $this->Session->read('c_cuenta_ban');
	$ano_mov  = $this->Session->read('c_ano');
	$mes_mov  = $this->Session->read('c_mes');

	$cond = $this->SQLCA()." and cod_entidad_bancaria=".$entidad." and cod_sucursal=".$sucursal." and cuenta_bancaria='".$cuenta."' and ano_movimiento='$ano_mov' and tipo_documento='$tipo_doc' and numero_documento=".$num_doc." and condicion_actividad=1";
	$datos = $this->v_cstd_mov_gral->findAll($cond,null,'tipo_documento, numero_documento ASC');

			if ($datos==null){
				$cond = $this->SQLCA()." and cod_entidad_bancaria=".$entidad." and cod_sucursal=".$sucursal." and cuenta_bancaria='".$cuenta."' and ano_movimiento='".($ano_mov-1)."' and tipo_documento='$tipo_doc' and numero_documento=".$num_doc." and condicion_actividad=1";
					$datos = $this->v_cstd_mov_gral->findAll($cond,null,'tipo_documento, numero_documento ASC');
				}

				if ($datos==null){
					$cond = $this->SQLCA()." and cod_entidad_bancaria=".$entidad." and cod_sucursal=".$sucursal." and cuenta_bancaria='".$cuenta."' and tipo_documento='$tipo_doc' and numero_documento=".$num_doc." and condicion_actividad=1";
						$datos = $this->v_cstd_mov_gral->findAll($cond,null,'tipo_documento, numero_documento ASC');
					}

		if($datos!=null){
			$this->set('existe',1);
			$this->set('mensaje','');
			$this->set('fecha_docu_tesoreria',$datos[0]['v_cstd_mov_gral']['fecha_documento']);
			$this->set('monto_docu_tesoreria',$datos[0]['v_cstd_mov_gral']['monto']);
			echo "<script>$('b_guardar').disabled=false;</script>";
		}else{
			$this->set('existe',0);
			switch($tipo_doc){
				case '1': $this->set('mensaje','DEPOSITO NO ENCONTRADO'); break;
				case '2': $this->set('existe',0); $this->set('mensaje','NOTA CREDITO NO ENCONTRADA');	break;
				case '3': $this->set('existe',0); $this->set('mensaje','NOTA DEBITO NO ENCONTRADA');	break;
				case '4': $this->set('existe',0); $this->set('mensaje','CHEQUE NO ENCONTRADO'); break;
			}//switch
			//echo "<script>$('b_guardar').disabled=true;</script>";
			echo "<script>$('b_guardar').disabled=false;</script>";
		}
}



function guardar($var=null){
	$this->layout = "ajax";

	$ano_mov          = (int) $this->data['cstp05_estado_cuentas']['ano_1'];
	$mes_mov          = (int) $this->data['cstp05_estado_cuentas']['mes'];
	$entidad          = (int) $this->data['cstp05_estado_cuentas']['cod_entidad_bancaria'];
	$sucursal         = (int) $this->data['cstp05_estado_cuentas']['cod_sucursal'];
	$cuenta           = $this->data['cstp05_estado_cuentas']['cod_cuenta'];
	$tipo_documento   = $this->data['cstp05_estado_cuentas']['tipodocumento'];
	$numero_documento = $this->data['cstp05_estado_cuentas']['numero_documento_banco'];
	$fecha_documento  = $this->data['cstp05_estado_cuentas']['fecha_documento_banco'];
	$monto            = $this->Formato1($this->data['cstp05_estado_cuentas']['monto_documento_banco']);

	if($this->anio_bisiesto($ano_mov)==true){$dia_feb="29";}else{$dia_feb="28";}

	switch($mes_mov){
    		case '1': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '2': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = $dia_feb."/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '3': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '4': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "30/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '5': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '6': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "30/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '7': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '8': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '9': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "30/0".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '10': $fecha_inicial = "01/".$mes_mov."/$ano_mov"; $fecha_final = "31/".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '11': $fecha_inicial = "01/".$mes_mov."/$ano_mov"; $fecha_final = "30/".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    		case '12': $fecha_inicial = "01/".$mes_mov."/$ano_mov"; $fecha_final = "31/".$mes_mov."/$ano_mov"; $fecha_anterior = date('d/m/Y', mktime(0, 0, 0, $mes_mov, 0, $ano_mov)); break;
    }

	$consulta_estado = $this->SQLCA()." and ano_movimiento='$ano_mov' and cod_entidad_bancaria='$entidad' and cod_sucursal='$sucursal' and cuenta_bancaria='$cuenta' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
	if($this->cstd05_estado_cuentas->findCount($consulta_estado)!=0){
		$documento = $this->cstd05_estado_cuentas->findAll($consulta_estado);
		$fecha = split('-',$documento[0]['cstd05_estado_cuentas']['fecha_documento']);
		$this->set('mensajeError','DISCULPE ESE DOCUMENTO YA SE ENCUENTRA REGISTRADO EN LA FECHA '.$fecha[2].'/'.$fecha[1].'/'.$fecha[0]);

	/*

		MODIFICACION DONDE SOLO SE LE QUITO EL ANO DE MOVIMIENTO Y AGREGO COMPARACIÓN DEL MONTO EN EL FILTRO.

				$sql = "select a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.tipo_documento, a.numero_documento, a.fecha_documento, a.monto,
	    			(select b.numero_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento	= b.numero_documento)::int4 as numero_tesoreria,
	    			(select b.fecha_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento	= b.numero_documento)::date as fecha_tesoreria,
	    			(select b.monto from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento = b.numero_documento)::numeric as monto_tesoreria
					from cstd05_estado_cuentas a
					where
					a.cod_presi 		= ".$this->Session->read('SScodpresi')."    and
					a.cod_entidad		= ".$this->Session->read('SScodentidad')."  and
					a.cod_tipo_inst 	= ".$this->Session->read('SScodtipoinst')." and
					a.cod_inst		    = ".$this->Session->read('SScodinst')."     and
					a.cod_dep		    = ".$this->Session->read('SScoddep')."      and
					a.cod_entidad_bancaria	= ".$entidad."  and
					a.cod_sucursal		    = ".$sucursal." and
					a.cuenta_bancaria		= '$cuenta' and a.fecha_documento BETWEEN '$fecha_inicial' AND '$fecha_final'
					order By a.fecha_documento, a.tipo_documento, a.numero_documento;";
    */

				$sql = "select a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.tipo_documento, a.numero_documento, a.fecha_documento, a.monto,
	    			(select b.numero_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento	= b.numero_documento)::int4 as numero_tesoreria,
	    			(select b.fecha_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento	= b.numero_documento)::date as fecha_tesoreria,
	    			(select b.monto from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento = b.numero_documento)::numeric as monto_tesoreria
					from cstd05_estado_cuentas a
					where
					a.cod_presi 		= ".$this->Session->read('SScodpresi')."    and
					a.cod_entidad		= ".$this->Session->read('SScodentidad')."  and
					a.cod_tipo_inst 	= ".$this->Session->read('SScodtipoinst')." and
					a.cod_inst		    = ".$this->Session->read('SScodinst')."     and
					a.cod_dep		    = ".$this->Session->read('SScoddep')."      and
					a.cod_entidad_bancaria	= ".$entidad."  and
					a.cod_sucursal		    = ".$sucursal." and
					a.cuenta_bancaria		= '$cuenta' and a.fecha_documento BETWEEN '$fecha_inicial' AND '$fecha_final'
					order By a.fecha_documento, a.tipo_documento, a.numero_documento;";

				$datos = $this->cstd05_estado_cuentas->execute($sql);

				$suma_dep_nc = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as suma_dep_nc FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$entidad' AND cod_sucursal='$sucursal' AND cuenta_bancaria='$cuenta' AND (tipo_documento='1' OR  tipo_documento='2') AND a.fecha_documento BETWEEN '01/01/1900' AND '$fecha_anterior'");
				$suma_che_nd = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as suma_che_nd FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$entidad' AND cod_sucursal='$sucursal' AND cuenta_bancaria='$cuenta' AND (tipo_documento='3' OR  tipo_documento='4') AND a.fecha_documento BETWEEN '01/01/1900' AND '$fecha_anterior'");
				$this->set('suma_dep_nc', isset($suma_dep_nc[0][0]['suma_dep_nc']) ? $suma_dep_nc[0][0]['suma_dep_nc'] : 0);
				$this->set('suma_che_nd', isset($suma_che_nd[0][0]['suma_che_nd']) ? $suma_che_nd[0][0]['suma_che_nd'] : 0);

				if($datos!=null){
					$this->set('datos',$datos);
					$this->set('error',0);
				}else{
					$this->set('datos',null);
					$this->set('error',0);
				}
				$this->mostrar_datos();
				$this->render('mostrar_datos');

	}else{

			$insert_estado = "INSERT INTO cstd05_estado_cuentas VALUES (".$this->Session->read('SScodpresi').",".$this->Session->read('SScodentidad').",".$this->Session->read('SScodtipoinst').",".$this->Session->read('SScodinst').",".$this->Session->read('SScoddep').",'$ano_mov','$entidad','$sucursal','$cuenta','$tipo_documento','$numero_documento','$fecha_documento','$monto')";
			$execute1 = $this->cstd05_estado_cuentas->execute($insert_estado);
			if($execute1>1){

				if($tipo_documento==4){
					$consulta_v_documento = $this->SQLCA()." and ano_movimiento='$ano_mov' and cod_entidad_bancaria='$entidad' and cod_sucursal='$sucursal' and cuenta_bancaria='$cuenta' and numero_documento='$numero_documento'";
					$tipo_cheque = $this->v_vistas_cheques_union->findAll($consulta_v_documento, array('tipo_cheque'));
					$tipocheq = $tipo_cheque[0]['v_vistas_cheques_union']['tipo_cheque'];
					if($tipocheq==1){
						$act_status_cheq="UPDATE cstd03_movimientos_manuales SET status='4' WHERE ".$consulta_v_documento." and tipo_documento=4";
						if($this->cstd03_movimientos_manuales->execute($act_status_cheq)>1){
						   $this->set('mensaje','EL DOCUMENTO FUE REGISTRADO CORRECTAMENTE');
						}else{
						   $this->set('mensaje','Atencion: EL DOCUMENTO FUE REGISTRADO PERO NO FUE CAMBIADO EL STATUS A PAGADO');
						}

					}elseif($tipocheq==2){
						$act_status_cheq="UPDATE cstd03_cheque_cuerpo SET status_cheque='4' WHERE ".$this->SQLCA()." and ano_movimiento='$ano_mov' and cod_entidad_bancaria='$entidad' and cod_sucursal='$sucursal' and cuenta_bancaria='$cuenta' and numero_cheque='$numero_documento'";
						if($this->cstd03_cheque_cuerpo->execute($act_status_cheq)>1){
						   $this->set('mensaje','EL DOCUMENTO fue REGISTRADO CORRECTAMENTE');
						}else{
						   $this->set('mensaje','Atencion: EL DOCUMENTO FUE REGISTRADO PERO NO PUDO SER CAMBIADO EL STATUS A PAGADO');
						}
					}

				}else{
					$this->set('mensaje','EL DOCUMENTO FUE REGISTRADO CORRECTAMENTE');
				}

				/*

				 MODIFICACION DONDE SOLO SE LE QUITO EL ANO DE MOVIMIENTO Y AGREGO COMPARACIÓN DEL MONTO EN EL FILTRO.

		    	$sql = "select a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.tipo_documento, a.numero_documento, a.fecha_documento, a.monto,
		    			(select b.numero_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento	= b.numero_documento)::int4 as numero_tesoreria,
		    			(select b.fecha_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento	= b.numero_documento)::date as fecha_tesoreria,
		    			(select b.monto from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento = b.numero_documento)::numeric as monto_tesoreria
						from cstd05_estado_cuentas a
						where
						a.cod_presi 		= ".$this->Session->read('SScodpresi')."    and
						a.cod_entidad		= ".$this->Session->read('SScodentidad')."  and
						a.cod_tipo_inst 	= ".$this->Session->read('SScodtipoinst')." and
						a.cod_inst		    = ".$this->Session->read('SScodinst')."     and
						a.cod_dep		    = ".$this->Session->read('SScoddep')."      and
						a.ano_movimiento	    = ".$ano_mov."  and
						a.cod_entidad_bancaria	= ".$entidad."  and
						a.cod_sucursal		    = ".$sucursal." and
						a.cuenta_bancaria		= '$cuenta'
						order By a.tipo_documento, a.numero_documento, a.fecha_documento;";
				*/

				$sql = "select a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.tipo_documento, a.numero_documento, a.fecha_documento, a.monto,
	    			(select b.numero_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento = b.numero_documento)::int4 as numero_tesoreria,
	    			(select b.fecha_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento = b.numero_documento)::date as fecha_tesoreria,
	    			(select b.monto from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento = b.numero_documento)::numeric as monto_tesoreria
					from cstd05_estado_cuentas a
					where
					a.cod_presi 		= ".$this->Session->read('SScodpresi')."    and
					a.cod_entidad		= ".$this->Session->read('SScodentidad')."  and
					a.cod_tipo_inst 	= ".$this->Session->read('SScodtipoinst')." and
					a.cod_inst		    = ".$this->Session->read('SScodinst')."     and
					a.cod_dep		    = ".$this->Session->read('SScoddep')."      and
					a.ano_movimiento	    = ".$ano_mov."  and
					a.cod_entidad_bancaria	= ".$entidad."  and
					a.cod_sucursal		    = ".$sucursal." and
					a.cuenta_bancaria		= '$cuenta' and a.fecha_documento BETWEEN '$fecha_inicial' AND '$fecha_final'
					order By a.fecha_documento, a.tipo_documento, a.numero_documento;";

				$datos = $this->cstd05_estado_cuentas->execute($sql);

				$suma_dep_nc = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as suma_dep_nc FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$entidad' AND cod_sucursal='$sucursal' AND cuenta_bancaria='$cuenta' AND (tipo_documento='1' OR  tipo_documento='2') AND a.fecha_documento BETWEEN '01/01/1900' AND '$fecha_anterior'");
				$suma_che_nd = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as suma_che_nd FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$entidad' AND cod_sucursal='$sucursal' AND cuenta_bancaria='$cuenta' AND (tipo_documento='3' OR  tipo_documento='4') AND a.fecha_documento BETWEEN '01/01/1900' AND '$fecha_anterior'");
				$this->set('suma_dep_nc', isset($suma_dep_nc[0][0]['suma_dep_nc']) ? $suma_dep_nc[0][0]['suma_dep_nc'] : 0);
				$this->set('suma_che_nd', isset($suma_che_nd[0][0]['suma_che_nd']) ? $suma_che_nd[0][0]['suma_che_nd'] : 0);

				if($datos!=null){
					$this->set('datos',$datos);
					$this->set('error',0);
				}else{
					$this->set('datos',null);
					$this->set('error',0);
				}
				$this->mostrar_datos();
				$this->render('mostrar_datos');

			}else{
				$this->set('mensajeError','LO SIENTO EL DOCUMENTO NO PUDO SER REGISTRADO');

				/*

		    	MODIFICACION DONDE SOLO SE LE QUITO EL ANO DE MOVIMIENTO Y AGREGO COMPARACIÓN DEL MONTO EN EL FILTRO.

		    	$sql = "select a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.tipo_documento, a.numero_documento, a.fecha_documento, a.monto,
		    			(select b.numero_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento	= b.numero_documento)::int4 as numero_tesoreria,
		    			(select b.fecha_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento	= b.numero_documento)::date as fecha_tesoreria,
		    			(select b.monto from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento = b.numero_documento)::numeric as monto_tesoreria
						from cstd05_estado_cuentas a
						where
						a.cod_presi 		= ".$this->Session->read('SScodpresi')."    and
						a.cod_entidad		= ".$this->Session->read('SScodentidad')."  and
						a.cod_tipo_inst 	= ".$this->Session->read('SScodtipoinst')." and
						a.cod_inst		    = ".$this->Session->read('SScodinst')."     and
						a.cod_dep		    = ".$this->Session->read('SScoddep')."      and
						a.ano_movimiento	    = ".$ano_mov."  and
						a.cod_entidad_bancaria	= ".$entidad."  and
						a.cod_sucursal		    = ".$sucursal." and
						a.cuenta_bancaria		= '$cuenta'
						order By a.tipo_documento, a.numero_documento, a.fecha_documento;";
				*/

				$sql = "select a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.tipo_documento, a.numero_documento, a.fecha_documento, a.monto,
	    			(select b.numero_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento = b.numero_documento)::int4 as numero_tesoreria,
	    			(select b.fecha_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento	= b.numero_documento)::date as fecha_tesoreria,
	    			(select b.monto from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento = b.numero_documento)::numeric as monto_tesoreria
					from cstd05_estado_cuentas a
					where
					a.cod_presi 		= ".$this->Session->read('SScodpresi')."    and
					a.cod_entidad		= ".$this->Session->read('SScodentidad')."  and
					a.cod_tipo_inst 	= ".$this->Session->read('SScodtipoinst')." and
					a.cod_inst		    = ".$this->Session->read('SScodinst')."     and
					a.cod_dep		    = ".$this->Session->read('SScoddep')."      and
					a.ano_movimiento	    = ".$ano_mov."  and
					a.cod_entidad_bancaria	= ".$entidad."  and
					a.cod_sucursal		    = ".$sucursal." and
					a.cuenta_bancaria		= '$cuenta' and a.fecha_documento BETWEEN '$fecha_inicial' AND '$fecha_final'
					order By a.fecha_documento, a.tipo_documento, a.numero_documento;";

				$datos = $this->cstd05_estado_cuentas->execute($sql);

				$suma_dep_nc = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as suma_dep_nc FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$entidad' AND cod_sucursal='$sucursal' AND cuenta_bancaria='$cuenta' AND (tipo_documento='1' OR  tipo_documento='2') AND a.fecha_documento BETWEEN '01/01/1900' AND '$fecha_anterior'");
				$suma_che_nd = $this->cstd05_estado_cuentas->execute("SELECT SUM(a.monto) as suma_che_nd FROM cstd05_estado_cuentas a WHERE ".$this->SQLCA()." AND cod_entidad_bancaria='$entidad' AND cod_sucursal='$sucursal' AND cuenta_bancaria='$cuenta' AND (tipo_documento='3' OR  tipo_documento='4') AND a.fecha_documento BETWEEN '01/01/1900' AND '$fecha_anterior'");
				$this->set('suma_dep_nc', isset($suma_dep_nc[0][0]['suma_dep_nc']) ? $suma_dep_nc[0][0]['suma_dep_nc'] : 0);
				$this->set('suma_che_nd', isset($suma_che_nd[0][0]['suma_che_nd']) ? $suma_che_nd[0][0]['suma_che_nd'] : 0);

				if($datos!=null){
					$this->set('datos',$datos);
					$this->set('error',0);
				}else{
					$this->set('datos',null);
					$this->set('error',0);
				}
				$this->mostrar_datos();
				$this->render('mostrar_datos');
			}
	}

	echo'<script>';
  	  echo"document.getElementById('b_guardar').disabled=false;";
  	  /* echo"document.getElementById('tipodocumento_1').checked=false;";
  	  echo"document.getElementById('tipodocumento_2').checked=false;";
  	  echo"document.getElementById('tipodocumento_3').checked=false;";
  	  echo"document.getElementById('tipodocumento_4').checked=false;"; */
  	  echo"document.getElementById('numero_documento_banco').value='';";
  	  echo"document.getElementById('fecha_documento_banco').value='';";
  	  echo"document.getElementById('monto_documento_banco').value='';";
  	  echo"document.getElementById('fecha_documento_tesoreria').value='';";
  	  echo"document.getElementById('monto_documento_tesoreria').value='';";
  	echo'</script>';
}

function mostrar_datos(){
	$this->layout="ajax";
}

function eliminar($ano_mov=null, $entidad=null, $sucursal=null, $cuenta=null, $tipo_doc=null, $num_doc=null, $k=null){
	$this->layout="ajax";

	$entidad;
	$sucursal;
	$cuenta;
	$tipo_doc;
	$num_doc;
    //$ano_mov = $this->Session->read('c_ano');
	$mes_mov = $this->Session->read('c_mes');


	$sql = "DELETE FROM cstd05_estado_cuentas where ".$this->SQLCA()." and cod_entidad_bancaria='$entidad' and cod_sucursal='$sucursal' and cuenta_bancaria='$cuenta' and ano_movimiento='$ano_mov' and tipo_documento='$tipo_doc' and numero_documento='$num_doc'";
	$result = $this->cstd05_estado_cuentas->execute($sql);
	if($result>1){
			if($tipo_doc==4){
					$consulta_v_documento = $this->SQLCA()." and ano_movimiento='$ano_mov' and cod_entidad_bancaria='$entidad' and cod_sucursal='$sucursal' and cuenta_bancaria='$cuenta' and numero_documento='$num_doc'";
					$tipo_cheque = $this->v_vistas_cheques_union->findAll($consulta_v_documento, array('tipo_cheque'));
					$tipocheq = $tipo_cheque[0]['v_vistas_cheques_union']['tipo_cheque'];
					if($tipocheq==1){
						$act_status_cheq="UPDATE cstd03_movimientos_manuales SET status='3' WHERE ".$consulta_v_documento." and tipo_documento=4";
						if($this->cstd03_movimientos_manuales->execute($act_status_cheq)>1){
						   $this->set('mensaje','EL DOCUMENTO FUE REGISTRADO CORRECTAMENTE');
						}else{
						   $this->set('mensaje','Atencion: EL DOCUMENTO FUE REGISTRADO PERO NO FUE CAMBIADO EL STATUS A PAGADO');
						}

					}elseif($tipocheq==2){
						$act_status_cheq="UPDATE cstd03_cheque_cuerpo SET status_cheque='3' WHERE ".$this->SQLCA()." and ano_movimiento='$ano_mov' and cod_entidad_bancaria='$entidad' and cod_sucursal='$sucursal' and cuenta_bancaria='$cuenta' and numero_cheque='$num_doc'";
						if($this->cstd03_cheque_cuerpo->execute($act_status_cheq)>1){
						   $this->set('mensaje','El registro fue eliminado correctamente');
						}else{
						   $this->set('mensajeError','Atencion: El registro no pudo ser eliminado correctamente');
						}
					}

			}else{
				$this->set('mensaje','El registro fue eliminado correctamente');
			}
	}else{
		$this->set('mensajeError','Lo siento, el registro no pudo ser eliminado');
	}

	if($this->anio_bisiesto($ano_mov)==true){$dia_feb="29";}else{$dia_feb="28";}

	    switch($mes_mov){
    		case '1': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; break;
    		case '2': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = $dia_feb."/0".$mes_mov."/$ano_mov"; break;
    		case '3': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; break;
    		case '4': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "30/0".$mes_mov."/$ano_mov"; break;
    		case '5': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; break;
    		case '6': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "30/0".$mes_mov."/$ano_mov"; break;
    		case '7': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; break;
    		case '8': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; break;
    		case '9': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "30/0".$mes_mov."/$ano_mov"; break;
    		case '10': $fecha_inicial = "01/".$mes_mov."/$ano_mov"; $fecha_final = "31/".$mes_mov."/$ano_mov"; break;
    		case '11': $fecha_inicial = "01/".$mes_mov."/$ano_mov"; $fecha_final = "30/".$mes_mov."/$ano_mov"; break;
    		case '12': $fecha_inicial = "01/".$mes_mov."/$ano_mov"; $fecha_final = "31/".$mes_mov."/$ano_mov"; break;
    	}

		/*

    	MODIFICACION DONDE SOLO SE LE QUITO EL ANO DE MOVIMIENTO Y AGREGO COMPARACIÓN DEL MONTO EN EL FILTRO.

    	$sql = "select a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.tipo_documento, a.numero_documento, a.fecha_documento, a.monto,
    			(select b.numero_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento	= b.numero_documento)::int4 as numero_tesoreria,
    			(select b.fecha_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento	= b.numero_documento)::date as fecha_tesoreria,
    			(select b.monto from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento = b.numero_documento)::numeric as monto_tesoreria
				from cstd05_estado_cuentas a
				where
				a.cod_presi 		= ".$this->Session->read('SScodpresi')."    and
				a.cod_entidad		= ".$this->Session->read('SScodentidad')."  and
				a.cod_tipo_inst 	= ".$this->Session->read('SScodtipoinst')." and
				a.cod_inst		    = ".$this->Session->read('SScodinst')."     and
				a.cod_dep		    = ".$this->Session->read('SScoddep')."      and
				a.ano_movimiento	    = ".$ano_mov."  and
				a.cod_entidad_bancaria	= ".$entidad."  and
				a.cod_sucursal		    = ".$sucursal." and
				a.cuenta_bancaria		= '$cuenta'
				order By a.tipo_documento, a.numero_documento, a.fecha_documento;";
		*/
		$sql = "select a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.tipo_documento, a.numero_documento, a.fecha_documento, a.monto,
	    			(select b.numero_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento = b.numero_documento)::int4 as numero_tesoreria,
	    			(select b.fecha_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento	= b.numero_documento)::date as fecha_tesoreria,
	    			(select b.monto from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento = b.numero_documento)::numeric as monto_tesoreria
					from cstd05_estado_cuentas a
					where
					a.cod_presi 		= ".$this->Session->read('SScodpresi')."    and
					a.cod_entidad		= ".$this->Session->read('SScodentidad')."  and
					a.cod_tipo_inst 	= ".$this->Session->read('SScodtipoinst')." and
					a.cod_inst		    = ".$this->Session->read('SScodinst')."     and
					a.cod_dep		    = ".$this->Session->read('SScoddep')."      and
					a.ano_movimiento	    = ".$ano_mov."  and
					a.cod_entidad_bancaria	= ".$entidad."  and
					a.cod_sucursal		    = ".$sucursal." and
					a.cuenta_bancaria		= '$cuenta' and a.fecha_documento BETWEEN '$fecha_inicial' AND '$fecha_final'
					order By a.fecha_documento, a.tipo_documento, a.numero_documento;";

		//$datos = $this->cstd05_estado_cuentas->execute($sql);

		echo "<script>new Effect.DropOut('tr_$k');</script>";
}

function eliminar2($cod_tipo=null, $k=null){
	$this->layout='ajax';
	if ($this->cpod00_tipo_beneficiario->execute("DELETE FROM cpod00_tipo_beneficiario WHERE cod_tipo='$cod_tipo'")>0) {
		echo "<script>new Effect.DropOut('tr_$k');</script>";
		$this->set('Message_existe','El tipo de beneficiario fu&eacute; eliminado correctamente');
	}else{
		$this->set('errorMessage','El tipo de beneficiario no pudo ser eliminado');
	}
}


function modificar($ano_mov=null, $entidad=null, $sucursal=null, $cuenta=null, $tipo_doc=null, $num_doc=null, $k=null, $fecha_teso=null, $monto_teso=null){
	$this->layout="ajax";
	$this->set('k',$k);

	$sql = $this->SQLCA()." and cod_entidad_bancaria=". $entidad." and cod_sucursal=".$sucursal." and cuenta_bancaria='$cuenta' and tipo_documento=".$tipo_doc." and numero_documento=".$num_doc;
	$datos = $this->cstd05_estado_cuentas->findAll($sql);

	$this->set('num_docu',$datos[0]['cstd05_estado_cuentas']['numero_documento']);
	$this->set('fecha_docu',$datos[0]['cstd05_estado_cuentas']['fecha_documento']);
	$this->set('monto_docu',$datos[0]['cstd05_estado_cuentas']['monto']);

	$this->set('fecha_teso',$fecha_teso);
	$this->set('monto_teso',$monto_teso);

	$this->set('ano_mov',$ano_mov);
	$this->set('entidad',$entidad);
	$this->set('sucursal',$sucursal);
	$this->set('cuenta',$cuenta);
	$this->set('tipo_doc',$tipo_doc);
	$this->set('num_doc',$num_doc);
}


function guardar_modificar($ano_mov=null, $entidad=null, $sucursal=null, $cuenta=null, $tipo_doc=null, $num_doc=null, $k=null, $fecha_teso=null, $monto_teso=null){
	$this->layout="ajax";

    $mes_mov  = $this->Session->read('c_mes');
	//$numero_documento = $this->data['cstp05_estado_cuentas']['numero_documento_banco_2'];
	$fecha_documento  = $this->data['cstp05_estado_cuentas']['fecha_documento_banco_2_'.$k.''];
	$monto            = $this->Formato1($this->data['cstp05_estado_cuentas']['monto_documento_banco_2_'.$k.'']);

	$update = "UPDATE cstd05_estado_cuentas SET fecha_documento='$fecha_documento', monto=".$monto." WHERE ".$this->SQLCA()." and ano_movimiento=".$ano_mov." and cod_entidad_bancaria=".$entidad." and cod_sucursal=".$sucursal." and  cuenta_bancaria='$cuenta' and tipo_documento=".$tipo_doc." and numero_documento=".$num_doc;
	$execute = $this->cstd05_estado_cuentas->execute($update);
	if($execute>1){
	$this->set('mensaje','El documento fue modificado correctamente');
	}else{
	$this->set('mensajeError','Lo siento, el documento no pudo ser modificado');
	}

	if($this->anio_bisiesto($ano_mov)==true){$dia_feb="29";}else{$dia_feb="28";}

   	switch($mes_mov){
    		case '1': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; break;
    		case '2': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = $dia_feb."/0".$mes_mov."/$ano_mov"; break;
    		case '3': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; break;
    		case '4': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "30/0".$mes_mov."/$ano_mov"; break;
    		case '5': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; break;
    		case '6': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "30/0".$mes_mov."/$ano_mov"; break;
    		case '7': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; break;
    		case '8': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "31/0".$mes_mov."/$ano_mov"; break;
    		case '9': $fecha_inicial = "01/0".$mes_mov."/$ano_mov"; $fecha_final = "30/0".$mes_mov."/$ano_mov"; break;
    		case '10': $fecha_inicial = "01/".$mes_mov."/$ano_mov"; $fecha_final = "31/".$mes_mov."/$ano_mov"; break;
    		case '11': $fecha_inicial = "01/".$mes_mov."/$ano_mov"; $fecha_final = "30/".$mes_mov."/$ano_mov"; break;
    		case '12': $fecha_inicial = "01/".$mes_mov."/$ano_mov"; $fecha_final = "31/".$mes_mov."/$ano_mov"; break;
    }

	/*

	MODIFICACION DONDE SOLO SE LE QUITO EL ANO DE MOVIMIENTO Y AGREGO COMPARACIÓN DEL MONTO EN EL FILTRO.

	$sql = "select a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.tipo_documento, a.numero_documento, a.fecha_documento, a.monto,
			(select b.numero_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento	= b.numero_documento)::int4 as numero_tesoreria,
			(select b.fecha_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento	= b.numero_documento)::date as fecha_tesoreria,
			(select b.monto from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.ano_movimiento = b.ano_movimiento and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.numero_documento = b.numero_documento)::numeric as monto_tesoreria
			from cstd05_estado_cuentas a
			where
			a.cod_presi 		= ".$this->Session->read('SScodpresi')."    and
			a.cod_entidad		= ".$this->Session->read('SScodentidad')."  and
			a.cod_tipo_inst 	= ".$this->Session->read('SScodtipoinst')." and
			a.cod_inst		    = ".$this->Session->read('SScodinst')."     and
			a.cod_dep		    = ".$this->Session->read('SScoddep')."      and
			a.ano_movimiento	    = ".$ano_mov."  and
			a.cod_entidad_bancaria	= ".$entidad."  and
			a.cod_sucursal		    = ".$sucursal." and
			a.cuenta_bancaria		= '$cuenta'
			order By a.tipo_documento, a.numero_documento, a.fecha_documento;";
	*/
	$sql = "select a.ano_movimiento, a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.tipo_documento, a.numero_documento, a.fecha_documento, a.monto,
	    			(select b.numero_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento	= b.numero_documento)::int4 as numero_tesoreria,
	    			(select b.fecha_documento from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento	= b.numero_documento)::date as fecha_tesoreria,
	    			(select b.monto from v_cstd_mov_gral b where a.cod_presi = b.cod_presi and a.cod_entidad = b.cod_entidad and a.cod_tipo_inst = b.cod_tipo_inst and a.cod_inst = b.cod_inst and a.cod_dep = b.cod_dep and a.cod_entidad_bancaria = b.cod_entidad_bancaria and a.cod_sucursal = b.cod_sucursal and a.cuenta_bancaria = b.cuenta_bancaria and a.tipo_documento = b.tipo_documento and a.monto = b.monto and a.numero_documento = b.numero_documento)::numeric as monto_tesoreria
					from cstd05_estado_cuentas a
					where
					a.cod_presi 		= ".$this->Session->read('SScodpresi')."    and
					a.cod_entidad		= ".$this->Session->read('SScodentidad')."  and
					a.cod_tipo_inst 	= ".$this->Session->read('SScodtipoinst')." and
					a.cod_inst		    = ".$this->Session->read('SScodinst')."     and
					a.cod_dep		    = ".$this->Session->read('SScoddep')."      and
					a.ano_movimiento	    = ".$ano_mov."  and
					a.cod_entidad_bancaria	= ".$entidad."  and
					a.cod_sucursal		    = ".$sucursal." and
					a.cuenta_bancaria		= '$cuenta' and a.fecha_documento BETWEEN '$fecha_inicial' AND '$fecha_final'
					order By a.fecha_documento, a.tipo_documento, a.numero_documento;";

	//$datos = $this->cstd05_estado_cuentas->execute($sql);

	/*
	if($datos!=null){
		$this->set('datos',$datos);
		$this->set('error',0);
	}else{
		$this->set('datos',null);
		$this->set('error',0);
	}
	*/

	$this->set('k',$k);
	$sql = $this->SQLCA()." and ano_movimiento=".$ano_mov." and cod_entidad_bancaria=". $entidad." and cod_sucursal=".$sucursal." and cuenta_bancaria='$cuenta' and tipo_documento=".$tipo_doc." and numero_documento=".$num_doc;
	$datos = $this->cstd05_estado_cuentas->findAll($sql);
	$this->set('num_docu',$datos[0]['cstd05_estado_cuentas']['numero_documento']);
	$this->set('fecha_docu',$datos[0]['cstd05_estado_cuentas']['fecha_documento']);
	$this->set('monto_docu',$datos[0]['cstd05_estado_cuentas']['monto']);
	$this->set('fecha_teso',$fecha_teso);
	$this->set('monto_teso',$monto_teso);
	$this->set('ano_mov',$ano_mov);
	$this->set('entidad',$entidad);
	$this->set('sucursal',$sucursal);
	$this->set('cuenta',$cuenta);
	$this->set('tipo_doc',$tipo_doc);
	$this->set('num_doc',$num_doc);
	//$this->mostrar_datos();
	//$this->render('mostrar_datos');

	$this->set("datos", $datos);
}


function reactualizar_estados_cuenta(){
	$this->layout="ajax";
	$cod_presi=$this->Session->read('SScodpresi');
	$cod_entidad=$this->Session->read('SScodentidad');
	$cod_tipo_inst=$this->Session->read('SScodtipoinst');
	$cod_inst=$this->Session->read('SScodinst');
	$cd=$this->Session->read('SScoddep');

	$condicion_institucion = "cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and tipo_documento=4";
	$datos_estado_cuentas  = $this->cstd05_estado_cuentas->findAll($condicion_institucion, null, null);
	//pr($datos_estado_cuentas);
	echo count($datos_estado_cuentas);
	foreach($datos_estado_cuentas as $d){
		$cp	= $d['cstd05_estado_cuentas']['cod_presi'];
		$ce= $d['cstd05_estado_cuentas']['cod_entidad'];
		$cti = $d['cstd05_estado_cuentas']['cod_tipo_inst'];
		$ci	= $d['cstd05_estado_cuentas']['cod_inst'];
		$cd	= $d['cstd05_estado_cuentas']['cod_dep'];

		$ano_mov = $d['cstd05_estado_cuentas']['ano_movimiento'];
		$entidad = $d['cstd05_estado_cuentas']['cod_entidad_bancaria'];
		$sucursal = $d['cstd05_estado_cuentas']['cod_sucursal'];
		$cuenta = $d['cstd05_estado_cuentas']['cuenta_bancaria'];
		$numero_documento = $d['cstd05_estado_cuentas']['numero_documento'];

		$consulta_v_documento = "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci' and cod_dep='$cd' and ano_movimiento='$ano_mov' and cod_entidad_bancaria='$entidad' and cod_sucursal='$sucursal' and cuenta_bancaria='$cuenta' and numero_documento='$numero_documento'";
		$tipo_cheque = $this->v_vistas_cheques_union->findAll($consulta_v_documento, array('tipo_cheque'));
		$tipocheq = $tipo_cheque[0]['v_vistas_cheques_union']['tipo_cheque'];
		if($tipocheq==1){
			$act_status_cheq="UPDATE cstd03_movimientos_manuales SET status='4' WHERE ".$consulta_v_documento." and tipo_documento=4";
			if($this->cstd03_movimientos_manuales->execute($act_status_cheq)>1){
			   $this->set('mensaje','EL DOCUMENTO FUE REGISTRADO CORRECTAMENTE');
			}else{
			   $this->set('mensaje','Atencion: EL DOCUMENTO FUE REGISTRADO PERO NO FUE CAMBIADO EL STATUS A PAGADO');
			}
		}elseif($tipocheq==2){
			$act_status_cheq="UPDATE cstd03_cheque_cuerpo SET status_cheque='4' WHERE cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci' and cod_dep='$cd' and ano_movimiento='$ano_mov' and cod_entidad_bancaria='$entidad' and cod_sucursal='$sucursal' and cuenta_bancaria='$cuenta' and numero_cheque='$numero_documento'";
			if($this->cstd03_cheque_cuerpo->execute($act_status_cheq)>1){
			   $this->set('mensaje','EL DOCUMENTO fue REGISTRADO CORRECTAMENTE');
			}else{
			   $this->set('mensaje','Atencion: EL DOCUMENTO FUE REGISTRADO PERO NO PUDO SER CAMBIADO EL STATUS A PAGADO');
			}
		}

	}

/*
	$cod_presi=$cp;
	$cod_entidad=$ce;
	$cod_tipo_inst=$cti;
	$cod_inst=$ci;
	$cod_dep=$cd;

	$ano_movimiento=$ano_mov;
	$cod_entidad_bancaria=$entidad;
	$cod_sucursal=$sucursal;
	$cuenta_bancaria=$cuenta;
	$numero_documento=$numero_documento;

	$condicion_institucion
*/
	/*
	$consulta_v_documento = $this->SQLCA()." and ano_movimiento='$ano_mov' and cod_entidad_bancaria='$entidad' and cod_sucursal='$sucursal' and cuenta_bancaria='$cuenta' and numero_documento='$numero_documento'";
	$tipo_cheque = $this->v_vistas_cheques_union->findAll($consulta_v_documento, array('tipo_cheque'));
	$tipocheq = $tipo_cheque[0]['v_vistas_cheques_union']['tipo_cheque'];
	if($tipocheq==1){
		$act_status_cheq="UPDATE cstd03_movimientos_manuales SET status='4' WHERE ".$consulta_v_documento." and tipo_documento=4";
		if($this->cstd03_movimientos_manuales->execute($act_status_cheq)>1){
		   $this->set('mensaje','EL DOCUMENTO FUE REGISTRADO CORRECTAMENTE');
		}else{
		   $this->set('mensaje','Atencion: EL DOCUMENTO FUE REGISTRADO PERO NO FUE CAMBIADO EL STATUS A PAGADO');
		}

	}elseif($tipocheq==2){
		$act_status_cheq="UPDATE cstd03_cheque_cuerpo SET status_cheque='4' WHERE ".$this->SQLCA()." and ano_movimiento='$ano_mov' and cod_entidad_bancaria='$entidad' and cod_sucursal='$sucursal' and cuenta_bancaria='$cuenta' and numero_cheque='$numero_documento'";
		if($this->cstd03_cheque_cuerpo->execute($act_status_cheq)>1){
		   $this->set('mensaje','EL DOCUMENTO fue REGISTRADO CORRECTAMENTE');
		}else{
		   $this->set('mensaje','Atencion: EL DOCUMENTO FUE REGISTRADO PERO NO PUDO SER CAMBIADO EL STATUS A PAGADO');
		}
	}
	*/
}

}
?>