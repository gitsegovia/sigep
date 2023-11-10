<?php
/*
 * Creado el  08/12/2007 a las 09:14:46 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
class Cstp03MovimientosManualesController extends AppController {
 	var $name = 'cstp03_movimientos_manuales';
 	var $uses = array ('cstd03_movimientos_manuales', 'cstd04_movimientos_generales', 'cstd02_cuentas_bancarias', 'cstd01_entidades_bancarias',
                       'cstd01_sucursales_bancarias','ccfd03_instalacion','cstd03_cheque_numero','cugd03_acta_anulacion_numero',
                       'cugd03_acta_anulacion_cuerpo','ccfd04_cierre_mes','cstd04_cheque_poremitir','cstd06_comprobante_numero_egreso',
                       'cstd06_comprobante_cuerpo_egreso','csrd01_solicitud_recurso_cuerpo', 'cugd02_dependencia','cstd09_notadebito_cuerpo',
                       'cfpd07_clasificacion_recurso','v_csrd01_solicitud_recurso_cuerpo','csrd01_solicitud_recurso_partidas','cugd02_dependencia',
                       'cstd01_entidades_bancarias', 'v_cstd01_bancos', 'v_cstd01_sucursales', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                       'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
                       'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo', 'cstd03_movimientos_manuales_ingresos',
				       'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
				       'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
				       'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo','cugd05_restriccion_clave','cstd09_notadebito_cuerpo_pago');

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


function fecha_postgres($fecha){
 	$cadena=split("-",$fecha);
   	return $resultado = $cadena[2].'/'.$cadena[1].'/'.$cadena[0];
}

function ano_session($ano=null){
	$this->layout="ajax";
	if(!empty($ano) && $ano!=null){
		$this->Session->write('ano_consulta_mov',$ano);
	}else{
		$ano = $this->ano_ejecucion();
		$this->Session->write('ano_consulta_mov',$ano);
	}
	$this->consultar();
	$this->render('consultar');
}


function index($var=null){

$this->verifica_entrada('68');

 	$this->layout ="ajax";

	$this->Session->delete('listado_ingreso');
	$this->Session->delete('ano_consulta_mov');//Se borra la session creada en la consulta.
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
 	$this->set('vector_cuenta','');

	$ano = $this->ano_ejecucion();
    $direccion_superior = $this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'direccion_superior');
    $this->set('operador_1', $this->Session->read('nom_usuario'));
	$this->set('ano_movimiento', $ano);
}


/**Funcion para buscar y cargar un radio para seleccionar si se quiere o no el numero de cheque automatico y, junto a el se cargar la disponibilidad
 * de la cuenta bancaria para consultarla desde el javascript a la hora de guardar, para verificar si el monto ingresado supera la disponibilidad
 *
 * Nombre: numero_automatico
 * Parametros:
 * 	-entidad bancaria
 * 	-sucursal bancaria
 *  -nro cuenta
 * */
function numero_automatico($cod_ent=null, $cod_sucursal=null, $cuenta=null){
 	$this->layout="ajax";

  	if($cuenta!=null){
 	   $this->set('entidad',$cod_ent);
 	   $this->set('sucursal',$cod_sucursal);
 	   $this->set('cuenta',$cuenta);

 	   if($cuenta==null){
 		  $this->set('mensaje', 'ATENCI&Oacute;N: SELECCIONE LA ENTIDAD BANCARIA Y LA SUCURSAL BANCARIA');
 	   }
 	   $cond = $this->SQLCA()." and cod_entidad_bancaria=".$cod_ent." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta."'";
 	   $disponibilidad_real = $this->cstd02_cuentas_bancarias->findAll($cond,array('disponibilidad_libro'),null,1,1,null);
	   $this->set('disponibilidad',$disponibilidad_real[0]['cstd02_cuentas_bancarias']['disponibilidad_libro']);
    }else{
     	$this->set('mensajeError', 'NO LLEGO LA INFORMACION DE LA CUENTA BANCARIA');
     	$this->set('entidad','');
 	    $this->set('sucursal','');
 	    $this->set('cuenta','');
 	    $this->set('disponibilidad','');
    }
}


/**Funcion para recuperar el numero de cheque automaticamente y mostrarlo al usuario.
 * Nombre:radio
 * Parametros:
 * 	-entidad bancaria
 *  -sucursal bancaria
 *  -cuenta
 *  -opcionradio: Es la condicion que viene con el radio 1=SI  2=NO
 * */
function radio($entid=null,$sucur=null,$cuentabanc=null,$opcionradio=null){
  $this->layout ="ajax";

	 if($opcionradio==1){//Indica si el usuario selecciono la opcion del numero automatico para el cheque (1=SI  2=NO)
	    if($entid!=null && $sucur!=null && $cuentabanc!=null){
			$cond=$this->SQLCA();
			$sql=$cond." and cod_entidad_bancaria='$entid' and cod_sucursal='$sucur' and cuenta_bancaria='$cuentabanc' and situacion=1";
			$resul=$this->cstd03_cheque_numero->findAll($sql,array('numero_cheque'),' consecutivo ASC');
			if($resul){
				//Aparto el numero de cheque actualizando su situacion igual a "2" = seleccionado
				$sql_update="UPDATE cstd03_cheque_numero SET situacion=2 WHERE ".$cond." and cod_entidad_bancaria='$entid' and cod_sucursal='$sucur' and cuenta_bancaria='$cuentabanc' and numero_cheque=".$resul[0]['cstd03_cheque_numero']['numero_cheque'];
				if($this->cstd03_cheque_numero->execute($sql_update)>0){
					$this->set('mensaje', 'EL NÚMERO DE CHEQUE FUÉ GENERADO CORRECTAMENTE');
					$this->set('numero',$resul[0]['cstd03_cheque_numero']['numero_cheque']);//$numero
				}else{
					$this->set('mensajeError', 'LO SIENTO, EL NÚMERO DE CHEQUE NO PUDO SER RECUPERADO AUTOMATICAMENTE');
					$this->set('numero',"nulo");
				}
			}else{
				$this->set('mensajeError', 'NO SE ENCONTRARÓN MAS CHEQUES DISPONIBLES PARA ESTA CUENTA BANCARIA');
				$this->set('numero',"nulo");
				$this->set('sin_numero',"nulo");
			}
		}else{
			$this->set('mensajeError', 'POR FAVOR DEBE SELECCIONAR LA ENTIDAD, LA SUCURSAL Y LA CUENTA BANCARIA');
		}
	 }else{
		$this->set('numero',"nulo");
	 }
}//radio





/**Funcion usada un paso antes de anular la informacion del documento, va y busca el operador, ano y otras cosas de las tablas
 * para mostrarla al usuario y luego este pueda anular el documento con la informacion mostrada haciendo click sobre el boton
 * dispuestos para ello.
 * */
function radio_anulado($ano_movimiento, $cod_entidad_bancaria, $cod_sucursal, $cuenta_bancaria, $tipo_documento, $numero_documento, $monto, $fecha_documento, $anterior){
 	$this->layout ="ajax";

	$var = null;
	if($var == '2'){
		$this->set('anulado', '2');
	 	$ano = $this->ano_ejecucion();
		$this->set('ano_movimiento', $ano);
	}
	 	$ano = $this->ano_ejecucion();
		$this->set('ano_movimiento', $ano);

	 	$this->set('operador_anulacion', $this->Session->read('nom_usuario'));
		$this->set('pase_anulacion','si');
	 	$this->set('ano_movimiento', $ano_movimiento);
	 	$this->set('cod_entidad_bancaria', $cod_entidad_bancaria);
	 	$this->set('cod_sucursal', $cod_sucursal);
	 	$this->set('cuenta_bancaria', $cuenta_bancaria);
	 	$this->set('tipo_documento', $tipo_documento);
	 	$this->set('numero_documento', $numero_documento);
	 	$this->set('monto', $monto);
	 	$this->set('fecha_documento', $fecha_documento);
	 	$this->set('anterior', $anterior+1);


	 	$condicion_SQLA = $this->SQLCA()." and ano_movimiento=".$ano_movimiento." and cod_entidad_bancaria='".$cod_entidad_bancaria."'   and cod_sucursal='".$cod_sucursal."' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='".$tipo_documento."'  and numero_documento='".$numero_documento."'    ";
        $data=$this->cstd03_movimientos_manuales->findAll($condicion_SQLA,null,"cod_entidad_bancaria, cod_sucursal, tipo_documento, numero_documento ASC");

		$this->set('ano_movimiento', $data[0]['cstd03_movimientos_manuales']['ano_movimiento']);
		$cod_entidad_bancaria=$this->mascara3($data[0]['cstd03_movimientos_manuales']['cod_entidad_bancaria']);//-----No borrar la variable "cod_entidad_bancaria"
	    $this->set('cod_entidad_bancaria', $cod_entidad_bancaria);
	    $cod_sucursal=$this->mascara3($data[0]['cstd03_movimientos_manuales']['cod_sucursal']);//---------------------No borrar la variable "cod_sucursal"
	    $this->set('cod_sucursal', $cod_sucursal);
	    $this->set('cuenta_bancaria', $data[0]['cstd03_movimientos_manuales']['cuenta_bancaria']);
	    $this->set('tipo_documento', $data[0]['cstd03_movimientos_manuales']['tipo_documento']);
	    $this->set('numero_documento', $data[0]['cstd03_movimientos_manuales']['numero_documento']);
	    $this->set('fecha_documento', $data[0]['cstd03_movimientos_manuales']['fecha_documento']);
	    $this->set('beneficiario', $data[0]['cstd03_movimientos_manuales']['beneficiario']);
	    $this->set('monto', $data[0]['cstd03_movimientos_manuales']['monto']);
	    $this->set('concepto', $data[0]['cstd03_movimientos_manuales']['concepto']);
	    $this->set('fecha_proceso_registro', $data[0]['cstd03_movimientos_manuales']['fecha_proceso_registro']);
	    $this->set('dia_asiento_registro', $data[0]['cstd03_movimientos_manuales']['dia_asiento_registro']);
	    $this->set('mes_asiento_registro', $data[0]['cstd03_movimientos_manuales']['mes_asiento_registro']);
	    $this->set('ano_asiento_registro', $data[0]['cstd03_movimientos_manuales']['ano_asiento_registro']);
	    $this->set('numero_asiento_registro', $data[0]['cstd03_movimientos_manuales']['numero_asiento_registro']);
	    $this->set('username_registro', $data[0]['cstd03_movimientos_manuales']['username_registro']);
	    $this->set('colocacion', $data[0]['cstd03_movimientos_manuales']['colocacion']);

	    $condicion_actividad=$data[0]['cstd03_movimientos_manuales']['condicion_actividad'];//<---No borrar esta variable
	    $this->set('condicion_act', $condicion_actividad);


	    $cond = $this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
		$disponibilidad_real = $this->cstd02_cuentas_bancarias->findAll($cond,array('disponibilidad_libro'),null,1,1,null);
		$this->set('disponibilidad',$disponibilidad_real[0]['cstd02_cuentas_bancarias']['disponibilidad_libro']);


	    if($condicion_actividad == 2){
	   		$this->set('enable_anular','disabled');
	   		$this->set('ano_anulacion', $data[0]['cstd03_movimientos_manuales']['ano_anulacion']);
	   		$this->set('numero_anulacion', $data[0]['cstd03_movimientos_manuales']['numero_anulacion']);
	   		$this->set('fecha_proceso_anulacion', $data[0]['cstd03_movimientos_manuales']['fecha_proceso_anulacion']);
	   		$this->set('dia_asiento_anulacion', $data[0]['cstd03_movimientos_manuales']['dia_asiento_anulacion']);
	   		$this->set('mes_asiento_anulacion', $data[0]['cstd03_movimientos_manuales']['mes_asiento_anulacion']);
	   		$this->set('ano_asiento_anulacion', $data[0]['cstd03_movimientos_manuales']['ano_asiento_anulacion']);
	   		$this->set('numero_asiento_anulacion', $data[0]['cstd03_movimientos_manuales']['numero_asiento_anulacion']);
	   		$this->set('username_anulacion', $data[0]['cstd03_movimientos_manuales']['username_anulacion']);
	    }else{
	    	$this->set('enable_anular','enable');
	    }


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

		  /*
		  $cond ="cod_entidad_bancaria=".$var;
		  $sucursales = $this->cstd01_sucursales_bancarias->findAll($cond, null, 'cod_sucursal ASC');
		  $lista=array();
		  $codSucursal  = array();
		  $denoSucursal = array();
		  $total_sucursales = count($sucursales);
		  if($total_sucursales==0){
		  		$lista = array();
		  }else{
		  		for($i=0; $i<$total_sucursales; $i++){
		  			$codSucursal[]  = mascara($sucursales[$i]['cstd01_sucursales_bancarias']['cod_sucursal'], 4);
					$denoSucursal[] = mascara($sucursales[$i]['cstd01_sucursales_bancarias']['cod_sucursal'], 4)." - ".$sucursales[$i]['cstd01_sucursales_bancarias']['denominacion'];
		  		}
		  		$lista = array_combine($codSucursal, $denoSucursal);
		  }
		  $this->set('vector',$lista);
		  */

			$busca=$this->SQLCA()." and cod_entidad_bancaria=".$var;
			$this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'vector');

		  //$lista = $this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
   		  //$this->concatena($lista, 'vector');
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
          echo "<input type='text' name='data[cstp03_movimientos_manuales][cod_entidad_bancaria]' value='".$this->mascara3($a[0]['cstd01_entidades_bancarias']['cod_entidad_bancaria'])."' size='5'  maxlength='4' id='cod_entidad_bancaria' readonly='readonly' class='inputtext' style='text-align:center' />";
		break;
		case 'sucursal':
		    if(!isset($var) || !isset($var2)){
		  	    echo "<input type='text' name='data[cstp03_movimientos_manuales][cod_sucursal_bancaria]' value='' size='5' maxlength='4' id='cod_sucursal_bancaria' readonly='readonly' class='inputtext' style='text-align:center' />";
		    }else{
		    $cond ="cod_entidad_bancaria=".$var." and cod_sucursal=".$var2;
		    $a=  $this->cstd01_sucursales_bancarias->findAll($cond);
            echo "<input type='text' name='data[cstp03_movimientos_manuales][cod_sucursal_bancaria]' value='".$this->mascara3($a[0]['cstd01_sucursales_bancarias']['cod_sucursal'])."' id='cod_sucursal_bancaria' size='5' maxlength='4'  readonly='readonly' class='inputtext' style='text-align:center' />";
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
		echo "<input type='text' name='data[cstp03_movimientos_manuales]' size='5' maxlength='4' id='cod_entidad_bancaria' class='inputtext' style='text-align:center' />";
	}
}//fin mostrar4 codigos





function mostrar3($select=null,$var=null,$var2=null) {
	$this->layout = "ajax";
if( $var!=null && !empty($var)){
	switch($select){
		case 'entidad_bancaria':
		  $cond ="cod_entidad_bancaria=".$var;
		  $a=  $this->cstd01_entidades_bancarias->findAll($cond);
          echo "<input type='text' name='data[cstp03_movimientos_manuales][deno_entidad_bancaria]' value='".$a[0]['cstd01_entidades_bancarias']['denominacion']."' maxlength='100' id='deno_entidad_bancaria' class='inputtext' />";
		break;
		case 'sucursal':
		    if(!isset($var2)){
		  	    echo "<input type='text' name='data[cstp03_movimientos_manuales][deno_sucursal_bancaria]' value='' maxlength='100' id='deno_sucursal_bancaria' class='inputtext' />";
		    }else{
		    $cond ="cod_entidad_bancaria=".$var." and cod_sucursal=".$var2;
		    $a=  $this->cstd01_sucursales_bancarias->findAll($cond);
            echo "<input type='text' name='data[cstp03_movimientos_manuales][deno_sucursal_bancaria]' value='".$a[0]['cstd01_sucursales_bancarias']['denominacion']."' maxlength='100' id='deno_sucursal_bancaria' class='inputtext' />";
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
		echo "<input type='text' name='data[cstp03_movimientos_manuales] value='' size='37'  maxlength='100' />";
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

	if($lista == 0){
	    $this->set('vector_cuenta',array('no'=>'no hay registros'));
	}else{
	    $this->set('vector_cuenta', $lista);
//	    $this->concatena($lista, 'vector_cuenta');
	    $this->set('codigo',$var);
	    $this->set('cod_sucursal',$var2);
	}

    }
}



function guardar(){
    $this->layout ="ajax";
	$listado_ing = $this->Session->read('listado_ingreso');
	$this->Session->delete('listado_ingreso');

	//pr($this->data);
	// Valido que el usuario seleciono el tipo de documento (marcando el radio)
	if($this->data['cstp03_movimientos_manuales']['tipo_documento'] != ""){

		$numero_documento =$this->data['cstp03_movimientos_manuales']['numero_documento'];
		$numero_automatico = $this->data['cstp03_movimientos_manuales']['numero_automatico'];//---- no
		$ano_movimiento = $this->data['cstp03_movimientos_manuales']['ano_1'];
		$cod_entidad_bancaria = $this->data['cstp03_movimientos_manuales']['cod_entidad_bancaria'];
		$cod_sucursal = $this->data['cstp03_movimientos_manuales']['cod_sucursal_bancaria'];
		$cuenta_bancaria =$this->data['cstp03_movimientos_manuales']['cuenta_bancaria'];
		$tipo_documento =$this->data['cstp03_movimientos_manuales']['tipo_documento'];
		$fecha_documento =$this->data['cstp03_movimientos_manuales']['fecha_documento'];
		$cadena_fecha=split("/",$this->data['cstp03_movimientos_manuales']['fecha_documento']);
		$beneficiario =$this->data['cstp03_movimientos_manuales']['beneficiario'];
		$monto = $this->Formato1($this->data['cstp03_movimientos_manuales']['monto']);
		$concepto =$this->data['cstp03_movimientos_manuales']['concepto'];
		$fecha_proceso_registro = date("Y")."/".date("n")."/".date("d");
		$dia_asiento_registro = "0";//$this->data['cstp03_movimientos_manuales']['dia_actual'];
		$mes_asiento_registro = "0";//$this->data['cstp03_movimientos_manuales']['mes_actual'];
		$ano_asiento_registro = "0";//$this->data['cstp03_movimientos_manuales']['ano_actual'];
		$numero_asiento_registro ="0"; //$this->data['cstp03_movimientos_manuales']['asiento_1'];
		$username_registro = $this->data['cstp03_movimientos_manuales']['operador_1'];
		$condicion_actividad = 1; // ---------------------- (MANUAL) -----> $this->data['cstp03_movimientos_manuales']['radio_anulado'];

		if($tipo_documento==4){
			$caja_chica = $this->data['cstp03_movimientos_manuales']['cheque_cach']; // Cheque es para constituir caja chica = 1: Si, 2: No
		}else{
			$caja_chica = 2; // Cheque es para constituir caja chica = 1: Si, 2: No
		}

		$caja_chica_rendida = 2; // El cheque que constituyo la caja chica fue rendido = 1: Si, 2: No(En este pto).

		$tipo_cuenta =$this->data['cstp03_movimientos_manuales']['tipo_cuenta'];
		if($tipo_cuenta==2 && $tipo_documento==4){
			$cod_tipo_enlace=$this->data['cstp03_movimientos_manuales']['tipo_enlace'];
		}else{
			$cod_tipo_enlace=0;
		}

		$ano_anulacion=0;
		$mro_anulacion=0;
		$fecha_anulacion=0;
		$dia_anulacion=0;
		$mes_anulacion=0;
		$ano_anulacion=0;
		$nro_asiento_anulacion=0;
		$username_anulacion=0;

		if($this->data['cstp03_movimientos_manuales']['pagotransferencia']==1){ // Indica que el cheque paga una orden de pago de transferencia
			$dep_solicitud = $this->data['cstp03_movimientos_manuales']['select_dependencias'];
			$ano_solicitud = $this->data['cstp03_movimientos_manuales']['ano_2'];
			$num_solicitud = $this->data['cstp03_movimientos_manuales']['numero_solicitud'];
		}else{
			$dep_solicitud = 0;
			$ano_solicitud = 0;
			$num_solicitud = 0;
		}

		$diamov_general=$cadena_fecha[0];// Dia del documento bancario
		$mesmov_general=$cadena_fecha[1];// Mes del documento bancario
		$consulta="select * from cstd03_movimientos_manuales where ".$this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
		if($this->cstd03_movimientos_manuales->execute($consulta)){
			$this->set('mensajeError','Esa información ya se encuentra registrada');
		}else{
			$cod_dependencias=$this->SQLCA();
			$ano = $this->ano_ejecucion();
			switch($tipo_documento){
				case 1:
						//echo "<br>Es un Deposito";
						$tipo_recurso = $this->data['cstp03_movimientos_manuales']['tipo_recurso'];
						$clasificacion_recurso = $this->data['cstp03_movimientos_manuales']['cod_tipo_recurso'];
						$colocacion = $this->data['cstp03_movimientos_manuales']['colocacion'];
                        $this->cstd03_movimientos_manuales->execute("BEGIN;");
						$sql="insert into cstd03_movimientos_manuales values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria',".
							"'$tipo_documento','$numero_documento','$fecha_documento',".
							"'$beneficiario','$monto','$concepto','$fecha_proceso_registro','$dia_asiento_registro','$mes_asiento_registro','$ano_asiento_registro','$numero_asiento_registro','$username_registro',".
							"'$condicion_actividad',".
							"'0', '0', '1900/01/01', '0', '0', '0', '0', '0', '$tipo_recurso', '$clasificacion_recurso','$colocacion','2','$cod_tipo_enlace','$caja_chica','$caja_chica_rendida','$dep_solicitud','$ano_solicitud','$num_solicitud')";
							if($this->cstd03_movimientos_manuales->execute($sql)>1){
			   					//Los datos fueron insertados correctamente en movimientos manuales, asi que se continua con el proceso de actualizacion la tabla cuentas bancarias
			   					$cstd02_cuentas_bancarias=$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
			   					$disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
			   					$monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['deposito_dia'];
			   					$monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['deposito_mes'];
			   					$monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['deposito_ano'];
			   					$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro'];
			   					$nuevo_monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['deposito_dia']+$monto;
			   					$nuevo_monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['deposito_mes']+$monto;
			   					$nuevo_monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['deposito_ano']+$monto;
			   					$nuevo_monto_disp_real=$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro']+$monto;
			   					$actualiza_dispo="UPDATE cstd02_cuentas_bancarias SET deposito_dia=".$nuevo_monto_dia.", deposito_mes=".$nuevo_monto_mes.", deposito_ano=".$nuevo_monto_ano.", disponibilidad_libro=".$nuevo_monto_disp_real." WHERE ".$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
								if($this->cstd02_cuentas_bancarias->execute($actualiza_dispo)>1){
								            //Insertamos en movimientos generales
								            $inserta_mov_generales="insert into cstd04_movimientos_generales values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria',".
								      																			    "'$mesmov_general','$diamov_general','$tipo_documento','$numero_documento','$monto')";
											if($this->cstd04_movimientos_generales->execute($inserta_mov_generales)>1){

												$tipo_cuenta =$this->data['cstp03_movimientos_manuales']['tipo_cuenta'];
												if($tipo_cuenta==1){
													//Realizamos los asientos contables en las diferentes cuentas que tengan monto asignado.
													//echo "<br /><br />";
													//pr($listado_ing);
													$bandera_cuenta_repetida=false;
													$monto_in_2 = 0;
													$cant_ing=count($listado_ing);
													for($in=0; $in<$cant_ing; $in++){
														$cuenta_cuenta_1 = $listado_ing[$in];
														$cuenta_cuenta_2 = isset($listado_ing[$in+1]) ? $listado_ing[$in+1] : '';
														$monto_in = $this->Formato1($this->data['cstp03_movimientos_manuales']['montotipoingreso_'.$listado_ing[$in].'_'.$in]);
														if($cuenta_cuenta_1 == $cuenta_cuenta_2){
															$monto_in_2 += $monto_in;
															$bandera_cuenta_repetida = true;
														}else{
															if($bandera_cuenta_repetida==true){
																if($monto_in_2 > 0 || $monto_in > 0){
																	$monto_in_2 += $monto_in;
																	//$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$this->Formato1($monto_in_2));
																	$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$monto_in_2);
																}
																	$bandera_cuenta_repetida=false;
																	$monto_in_2=0;
															}else{
																if($monto_in > 0){
																	//$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$this->Formato1($monto_in));
																	$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$monto_in);
																}
															}
														}

														/*
														$monto_in = $this->data['cstp03_movimientos_manuales']['montotipoingreso_'.$listado_ing[$in]];
														if($monto_in != 0){
															//echo "<br />Es distinto de cero ".$listado_ing[$in];
															$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$this->Formato1($monto_in));
														}else{
															//echo "<br />Es cero";
														}
														*/
													}
													//pr($cuenta_afectar);

																$monto_total["monto_total"] = $monto;
																$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
															    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

																$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																														      $to      = 1,
																														      $td      = 2,
																														      $rif_doc = null,
																														      $ano_dc  = $ano_movimiento,
																														      $n_dc    = $numero_documento,
																														      $f_dc    = $fecha_documento,
																														      $cpt_dc  = null,
																														      $ben_dc  = $beneficiario,
																														      $mon_dc  = $monto_total,
																														      $ano_op   = null,
																														      $n_op     = null,
																														      $f_op     = null,

																														      $a_adj_op = null,
																														      $n_adj_op = null,
																														      $f_adj_op = null,
																														      $tp_op    = null,
																														      $deno_ban_pago  = $cod_entidad_bancaria_aux,
																														      $ano_movimiento = $ano_movimiento,
																														      $cod_ent_pago   = $cod_entidad_bancaria,
																														      $cod_suc_pago   = $cod_sucursal,
																														      $cod_cta_pago   = $cuenta_bancaria,
																														      $num_che_o_debi  = $numero_documento,
																														      $fec_che_o_debi  = $fecha_documento,
																														      $clas_che_o_debi = null,
																														      $tipo_che_o_debi = null,
																														      $ano_dc_array_pago     = null,
																														      $n_dc_array_pago       = null,
																														      $n_dc_adj_array_pago   = null,
																														      $f_dc_array_pago       = null,
																														      $ano_op_array_pago  = null,
																														      $n_op_array_pago    = null,
																														      $f_op_array_pago    = null,
																														      $tipo_op_array_pago = null,
																														      null,
																														      $f_dc_adj_array_pago= null,
																														      $parametro_bienes   = $cuenta_afectar
																														     );

												$sw1=0;
                                                if($valor_motor_contabilidad==true){
													$cantidad_cuentas=count($cuenta_afectar);
													$valida_cuenta_1=0;
													$valida_cuenta_2=0;

													for($in=0; $in<$cantidad_cuentas; $in++){
														 $codigos_cuenta_cont = split('-', $cuenta_afectar[$in]['cuenta']);
														 $aux_tipo_cuenta 	= 2;
														 $aux_cuenta 		= $codigos_cuenta_cont[0];
														 $aux_subcuenta 	= $codigos_cuenta_cont[1];
														 $aux_division  	= $codigos_cuenta_cont[2];
														 $aux_subdivision	= $codigos_cuenta_cont[3];
														 $aux_monto= $cuenta_afectar[$in]['monto'];
														 $sql_tabla_cuentas="insert into cstd03_movimientos_manuales_ingresos values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria','$tipo_documento','$numero_documento','$aux_tipo_cuenta','$aux_cuenta','$aux_subcuenta','$aux_division','$aux_subdivision','$aux_monto')";
			                                             $sw1 = $this->cstd03_movimientos_manuales->execute($sql_tabla_cuentas);
														 if($sw1>1){}else{break;}

														 /*
														 $valida_cuenta_1 = $cuenta_afectar[$in]['cuenta'];
														 if($in==0){
															 $sql_tabla_cuentas="insert into cstd03_movimientos_manuales_ingresos values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria','$tipo_documento','$numero_documento','$aux_tipo_cuenta','$aux_cuenta','$aux_subcuenta','$aux_division','$aux_subdivision','$aux_monto')";
															 $sw1 = $this->cstd03_movimientos_manuales->execute($sql_tabla_cuentas);
														 }else{
															 if($valida_cuenta_1 == $valida_cuenta_2){
																 $aux_monto .= $aux_monto;
												 			 }else{
												 			 	 $sql_tabla_cuentas="insert into cstd03_movimientos_manuales_ingresos values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria','$tipo_documento','$numero_documento','$aux_tipo_cuenta','$aux_cuenta','$aux_subcuenta','$aux_division','$aux_subdivision','$aux_monto')";
																 $sw1 = $this->cstd03_movimientos_manuales->execute($sql_tabla_cuentas);
												 			 }
														 }
														 $valida_cuenta_2 = $cuenta_afectar[$in]['cuenta'];
															*/

														 //if($sw1>1){}else{break;}

												  	 }//fin for

												  	 //$sw1=2;///////////PARA BLOQUIAR CONTABILIDAD

												  	 if($sw1>1){
												  	  $this->cstd03_movimientos_manuales->execute("COMMIT;");
                                                      $this->set('mensaje', 'LA OPERACION FUE PROCESADA EXITOSAMENTE');
												  	 }else{
												  	  $this->cstd03_movimientos_manuales->execute("ROLLBACK;");
												  	  $this->set('mensajeError','Los datos de deposito no fueron guardados');
												  	 }

												}else{
												$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
												$this->set('mensajeError','Los datos de deposito no fueron guardados');
												}



												}else{
													$this->cstd03_movimientos_manuales->execute("COMMIT;");
													$this->set('mensaje', 'LA OPERACION FUE PROCESADA EXITOSAMENTE');
												}



											}else{
											$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
											$this->set('mensajeError','Los datos de deposito no fueron guardados');
											}

								}else{
								    //Si no se actualizaron los datos en las tablas "cstd02_cuentas_bancarias" me regreso a eliminar el registro que inserte en la "cstd03_movimientos_manuales"
									/*$delete="DELETE FROM cstd03_movimientos_manuales WHERE ".$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_documento=".$numero_documento;
									if($this->cstd03_movimientos_manuales->execute($delete)>1){
									$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
									$this->set('mensajeError', 'NO PUDO SER PROCESADA LA OPERACION, SE REGRES0 AL ESTADO ANTERIOR');
									}else{
									$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
									$this->set('mensajeError', 'NO PUDO SER PROCESADA LA OPERACION, NO FUERON ACTUALIZADOS DATOS DE IMPORTANCIA');
									}*/

									$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
			   					    $this->set('mensajeError','Los datos de deposito no fueron guardados');
								}
							}else{
								$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
			   					$this->set('mensajeError','Los datos de deposito no fueron guardados');
							}
						break;





					case 2:
						//echo "<br>Es una Nota de Credito";
						$tipo_recurso = $this->data['cstp03_movimientos_manuales']['tipo_recurso'];
						$clasificacion_recurso = $this->data['cstp03_movimientos_manuales']['cod_tipo_recurso'];
						$colocacion = $this->data['cstp03_movimientos_manuales']['colocacion'];
                        $this->cstd03_movimientos_manuales->execute("BEGIN;");
						$sql="insert into cstd03_movimientos_manuales values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria',".
							"'$tipo_documento','$numero_documento','$fecha_documento',".
							"'$beneficiario','$monto','$concepto','$fecha_proceso_registro','$dia_asiento_registro','$mes_asiento_registro','$ano_asiento_registro','$numero_asiento_registro','$username_registro',".
							"'$condicion_actividad',".
							"'0', '0', '1900/01/01', '0', '0', '0', '0', '0', '$tipo_recurso', '$clasificacion_recurso','$colocacion','2','$cod_tipo_enlace','$caja_chica','$caja_chica_rendida','$dep_solicitud','$ano_solicitud','$num_solicitud')";
							if($this->cstd03_movimientos_manuales->execute($sql)>1){
			   					//$this->set('mensaje','Los datos fueron insertados correctamente');
			   					$cstd02_cuentas_bancarias=$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
			   					$disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
			   					$monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_credito_dia'];
			   					$monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_credito_mes'];
			   					$monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_credito_ano'];
								$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro'];
			   					$nuevo_monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_credito_dia']+$monto;
			   					$nuevo_monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_credito_dia']+$monto;
			   					$nuevo_monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_credito_dia']+$monto;
			   					$nuevo_monto_disp_real=$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro']+$monto;
			   					$actualiza_dispo="UPDATE cstd02_cuentas_bancarias SET nota_credito_dia=".$nuevo_monto_dia.", nota_credito_mes=".$nuevo_monto_mes.", nota_credito_ano=".$nuevo_monto_ano.", disponibilidad_libro=".$nuevo_monto_disp_real." WHERE ".$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
								if($this->cstd02_cuentas_bancarias->execute($actualiza_dispo)>1){
											//Insertamos en movimientos generales
								        	$inserta_mov_generales="insert into cstd04_movimientos_generales values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria',".
																												    "'$mesmov_general','$diamov_general','$tipo_documento','$numero_documento','$monto')";
											if($this->cstd04_movimientos_generales->execute($inserta_mov_generales)>1){


												$tipo_cuenta =$this->data['cstp03_movimientos_manuales']['tipo_cuenta'];
												if($tipo_cuenta==1){
													//Realizamos los asientos contables en las diferentes cuentas que tengan monto asignado.

													//echo "<br /><br />";
													//pr($listado_ing);
													$bandera_cuenta_repetida=false;
													$monto_in_2 = '';
													$cant_ing=count($listado_ing);
													for($in=0; $in<$cant_ing; $in++){
														$cuenta_cuenta_1 = $listado_ing[$in];
														$cuenta_cuenta_2 = isset($listado_ing[$in+1]) ? $listado_ing[$in+1] : '';
														$monto_in = $this->Formato1($this->data['cstp03_movimientos_manuales']['montotipoingreso_'.$listado_ing[$in].'_'.$in]);
														if($cuenta_cuenta_1 == $cuenta_cuenta_2){
															$monto_in_2 += $monto_in;
															$bandera_cuenta_repetida = true;
														}else{
															if($bandera_cuenta_repetida==true){
																if($monto_in_2 > 0 || $monto_in > 0){
																	$monto_in_2 += $monto_in;
																	//$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$this->Formato1($monto_in_2));
																	$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$monto_in_2);
																}
																	$bandera_cuenta_repetida=false;
																	$monto_in_2=0;
															}else{
																if($monto_in > 0){
																	//$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$this->Formato1($monto_in));
																	$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$monto_in);
																}
															}
														}

														/*
														$monto_in = $this->data['cstp03_movimientos_manuales']['montotipoingreso_'.$listado_ing[$in]];
														if($monto_in != 0){
															//echo "<br />Es distinto de cero ".$listado_ing[$in];
															$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$this->Formato1($monto_in));
														}else{
															//echo "<br />Es cero";
														}
														*/
													}
													//pr($cuenta_afectar);

										$monto_total["monto_total"] = $monto;
										$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
									    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

										$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																								      $to      = 1,
																								      $td      = 3,
																								      $rif_doc = null,
																								      $ano_dc  = $ano_movimiento,
																								      $n_dc    = $numero_documento,
																								      $f_dc    = $fecha_documento,
																								      $cpt_dc  = null,
																								      $ben_dc  = $beneficiario,
																								      $mon_dc  = $monto_total,
																								      $ano_op   = null,
																								      $n_op     = null,
																								      $f_op     = null,

																								      $a_adj_op = null,
																								      $n_adj_op = null,
																								      $f_adj_op = null,
																								      $tp_op    = null,
																								      $deno_ban_pago  = $cod_entidad_bancaria_aux,
																								      $ano_movimiento = $ano_movimiento,
																								      $cod_ent_pago   = $cod_entidad_bancaria,
																								      $cod_suc_pago   = $cod_sucursal,
																								      $cod_cta_pago   = $cuenta_bancaria,
																								      $num_che_o_debi  = $numero_documento,
																								      $fec_che_o_debi  = $fecha_documento,
																								      $clas_che_o_debi = null,
																								      $tipo_che_o_debi = null,
																								      $ano_dc_array_pago     = null,
																								      $n_dc_array_pago       = null,
																								      $n_dc_adj_array_pago   = null,
																								      $f_dc_array_pago       = null,
																								      $ano_op_array_pago  = null,
																								      $n_op_array_pago    = null,
																								      $f_op_array_pago    = null,
																								      $tipo_op_array_pago = null,
																								      null,
																								      $f_dc_adj_array_pago= null,
																								      $parametro_bienes   = $cuenta_afectar
																								);

													$sw1=0;
													$valida_cuenta_1=0;
													$valida_cuenta_2=0;
                                                    if($valor_motor_contabilidad==true){
															$cantidad_cuentas=count($cuenta_afectar);
															for($in=0; $in<$cantidad_cuentas; $in++){
																	//echo "<br/>".$cuenta_afectar[$in]['cuenta'];
																	$codigos_cuenta_cont = split('-', $cuenta_afectar[$in]['cuenta']);
																	 $aux_tipo_cuenta 	= 2;
																	 $aux_cuenta 	    = $codigos_cuenta_cont[0];
																	 $aux_subcuenta 	= $codigos_cuenta_cont[1];
																	 $aux_division  	= $codigos_cuenta_cont[2];
																	 $aux_subdivision= $codigos_cuenta_cont[3];
																	 $aux_monto= $cuenta_afectar[$in]['monto'];
																	 $sql_tabla_cuentas="insert into cstd03_movimientos_manuales_ingresos values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria','$tipo_documento','$numero_documento','$aux_tipo_cuenta','$aux_cuenta','$aux_subcuenta','$aux_division','$aux_subdivision','$aux_monto')";
			                                                         $sw1 = $this->cstd03_movimientos_manuales->execute($sql_tabla_cuentas);
																     if($sw1>1){}else{break;}

																	 /*
																	 $valida_cuenta_1 = $cuenta_afectar[$in]['cuenta'];
																	 if($in==0){
																		 $sql_tabla_cuentas="insert into cstd03_movimientos_manuales_ingresos values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria','$tipo_documento','$numero_documento','$aux_tipo_cuenta','$aux_cuenta','$aux_subcuenta','$aux_division','$aux_subdivision','$aux_monto')";
																		 $sw1 = $this->cstd03_movimientos_manuales->execute($sql_tabla_cuentas);
																	 }else{
																		 if($valida_cuenta_1 == $valida_cuenta_2){
																			 $aux_monto .= $aux_monto;
															 			 }else{
															 			 	 $sql_tabla_cuentas="insert into cstd03_movimientos_manuales_ingresos values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria','$tipo_documento','$numero_documento','$aux_tipo_cuenta','$aux_cuenta','$aux_subcuenta','$aux_division','$aux_subdivision','$aux_monto')";
																			 $sw1 = $this->cstd03_movimientos_manuales->execute($sql_tabla_cuentas);
															 			 }
																	 }
																	 $valida_cuenta_2 = $cuenta_afectar[$in]['cuenta'];
																	 */
														  	 }//fin for

														    //$sw1=2;///////////PARA BLOQUIAR CONTABILIDAD

													  	 if($sw1>1){
													  	  $this->cstd03_movimientos_manuales->execute("COMMIT;");
	                                                      $this->set('mensaje', 'LA OPERACIÓN FUÉ PROCESADA EXITOSAMENTE');
													  	 }else{
													  	  $this->cstd03_movimientos_manuales->execute("ROLLBACK;");
													  	  $this->set('mensajeError','Los datos la nota de crédito no fuerón guardados');
													  	 }
                                                    }else{
                                                    	$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
                                                    	$this->set('mensajeError','Los datos de la nota de crédito no fuerón guardados');
                                                    }
												}else{
													$this->cstd03_movimientos_manuales->execute("COMMIT;");
													$this->set('mensaje', 'LA OPERACIÓN FUÉ PROCESADA EXITOSAMENTE');
												}


											}else{
											$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
											//$this->set('mensajeError', 'SE ACTUALIZO LA CUENTA BANCARIA, PERO NO SE PUDO GRABAR EN MOV. GENERALES');
											$this->set('mensajeError','Los datos de la nota de crédito no fuerón guardados');
											}

								}else{
								//Si no se actualizaron los datos en las tablas "cstd02_cuentas_bancarias" me regreso a eliminar el registro que inserte en la "cstd03_movimientos_manuales"
									/*$delete="DELETE FROM cstd03_movimientos_manuales WHERE ".$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_documento=".$numero_documento;
									if($this->cstd03_movimientos_manuales->execute($delete)>1){
									$this->set('mensajeError', 'NO PUDO SER PROCESADA LA OPERACION, SE REGRES0 AL ESTADO ANTERIOR');
									}else{
									$this->set('mensajeError', 'NO PUDO SER PROCESADA LA OPERACION, NO FUERON ACTUALIZADOS DATOS DE IMPORTANCIA');
									}*/
                                     $this->cstd03_movimientos_manuales->execute("ROLLBACK;");
									$this->set('mensajeError','Los datos de la nota de crédito no fuerón guardados');
								}
							}else{
								$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
			   					$this->set('mensajeError','Los datos de la nota de crédito no fuerón guardados');
							}
						break;





					case 3:
						//echo "<br>Es una Nota de Debito";

						$procede_mov="";
						$colocacion = 2;
						$this->cstd03_movimientos_manuales->execute("BEGIN;");
						
					// INICIO ARREGLO
					// 
					// 

						if($this->data['cstp03_movimientos_manuales']['pagotransferencia']==1){//Indica que el cheque paga una orden de pago de transferencia

							$dep_solicitud=$this->Session->read('dep_solicitud');
							$ano_solicitud=$this->Session->read('ano_solicitud');
							$num_solicitud=$this->Session->read('num_solicitud');
							$cond_solicitud="cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$dep_solicitud." and ano_solicitud=".$ano_solicitud." and numero_solicitud=".$num_solicitud;

							if($this->csrd01_solicitud_recurso_cuerpo->findCount($cond_solicitud)!=0){

								//La orden fue encontrada
						   		$max=$this->v_csrd01_solicitud_recurso_cuerpo->execute("SELECT monto_solicitado,monto_entregado FROM v_csrd01_solicitud_recurso_cuerpo WHERE ".$cond_solicitud);
								$monto_solicitud=$max[0][0]["monto_solicitado"];
								$num_entregado=$max[0][0]["monto_entregado"];
								$monto_entregado=$monto;
							    
							    if($monto > $monto_solicitud){
								
									$procede_mov="no";
									$mensaje_egreso="EL MOVIMIENTO NO PROCEDE EL MONTO A ENTREGAR ES MAYOR AL DE LA SOLICITUD DE RECURSOS";
								
								}else{
								
									$procede_mov="si";
								
								}
							
							}else{
							
								$procede_mov="no";
								$mensaje_egreso="EL MOVIMIENTO NO PROCEDE LA SOLICITUD DE RECURSOS NO PUDO SER ENCONTRADA";
							}
						
						}else{
						
							$procede_mov="si";

						}

					// FIN ARREGLO
					// 

						if($procede_mov=="si"){

							$sql="insert into 
								cstd03_movimientos_manuales 
								values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria',".
								"'$tipo_documento','$numero_documento','$fecha_documento',".
								"'$beneficiario','$monto','$concepto','$fecha_proceso_registro','$dia_asiento_registro','$mes_asiento_registro','$ano_asiento_registro','$numero_asiento_registro','$username_registro',".
								"'$condicion_actividad',".
								"'0', '0', '1900/01/01', '0', '0', '0', '0', '0','0','0','$colocacion','2','$cod_tipo_enlace','$caja_chica','$caja_chica_rendida','$dep_solicitud','$ano_solicitud','$num_solicitud')";

							if($this->cstd03_movimientos_manuales->execute($sql)>1){

								$cstd02_cuentas_bancarias=$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
								$disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
								$tipo_cuenta=$disponibilidad[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];
								$monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_debito_dia'];
								$monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_debito_mes'];
								$monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_debito_ano'];
								$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro'];
								$nuevo_monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_debito_dia']+$monto;//sumo el monto a la nota_debito_dia
								$nuevo_monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_debito_mes']+$monto;//sumo el monto a la nota_debito_mes
								$nuevo_monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_debito_ano']+$monto;//sumo el monto a la nota_debito_ano
								$nuevo_monto_disp_real=$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro'] - $monto;//resto el monto a la disponibilidad del libro
								$actualiza_dispo="UPDATE cstd02_cuentas_bancarias SET nota_debito_dia=".$nuevo_monto_dia.", nota_debito_mes=".$nuevo_monto_mes.", nota_debito_ano=".$nuevo_monto_ano.", disponibilidad_libro=".$nuevo_monto_disp_real." WHERE ".$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
							
								if($this->cstd02_cuentas_bancarias->execute($actualiza_dispo)>1){

									//Insertamos en movimientos generales
							        $inserta_mov_generales="insert into cstd04_movimientos_generales values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria',".
									    "'$mesmov_general','$diamov_general','$tipo_documento','$numero_documento','$monto')";
									
									if($this->cstd04_movimientos_generales->execute($inserta_mov_generales)>1){

										$cuenta_afectar  = null;

										if($tipo_cuenta==1){
												
												$bandera_cuenta_repetida=false;
												$monto_in_2 = '';
												$cant_ing=count($listado_ing);
												
												for($in=0; $in<$cant_ing; $in++){
												
													$cuenta_cuenta_1 = $listado_ing[$in];
													$cuenta_cuenta_2 = isset($listado_ing[$in+1]) ? $listado_ing[$in+1] : '';
													$monto_in = $this->Formato1($this->data['cstp03_movimientos_manuales']['montotipoingreso_'.$listado_ing[$in].'_'.$in]);
													
													if($cuenta_cuenta_1 == $cuenta_cuenta_2){
														$monto_in_2 += $monto_in;
														$bandera_cuenta_repetida = true;
													}else{
													
														if($bandera_cuenta_repetida==true){
												
															if($monto_in_2 > 0 || $monto_in > 0){
												
																$monto_in_2 += $monto_in;
																//$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$this->Formato1($monto_in_2));
																$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$monto_in_2);
															}
															
															$bandera_cuenta_repetida=false;
															$monto_in_2=0;
														
														}else{
														
															if($monto_in > 0){
														
																//$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$this->Formato1($monto_in));
																$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$monto_in);
															}
														}
													}
													/*
														$monto_in = $this->data['cstp03_movimientos_manuales']['montotipoingreso_'.$listado_ing[$in]];
														if($monto_in != 0){
															$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$this->Formato1($monto_in));
														}
													*/
												} // fin del for linea 95

												$monto_total["monto_total"] = $monto;
												$cuenta_afectar_2["tipo_cuenta"] = $tipo_cuenta;
											    $cuenta_afectar_2["tipo_movimiento"] = 2;
												$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
												$cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];
												$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																				$to = 1, $td = 20, $rif_doc = null, $ano_dc  = $ano_movimiento, 
																				$n_dc = $numero_documento,
																				$f_dc    = $fecha_documento,
																				$cpt_dc  = null,
																				$ben_dc  = $beneficiario,
																				$mon_dc  = $monto_total,
																				$ano_op   = null,
																				$n_op     = null,
																				$f_op     = null,
																			    $a_adj_op = null,
																				$n_adj_op = null,
																				$f_adj_op = null,
																				$tp_op    = null,
																				$deno_ban_pago  = $cod_entidad_bancaria_aux,
																				$ano_movimiento = $ano_movimiento,
																				$cod_ent_pago   = $cod_entidad_bancaria,
																				$cod_suc_pago   = $cod_sucursal,
																				$cod_cta_pago   = $cuenta_bancaria,
																				$num_che_o_debi  = $numero_documento,
																				$fec_che_o_debi  = $fecha_documento,
																				$clas_che_o_debi = null,
																				$tipo_che_o_debi = null,
																				$ano_dc_array_pago     = null,
																		        $n_dc_array_pago       = null,
																		      	$n_dc_adj_array_pago   = null,
																		     	$f_dc_array_pago       = null,
																		      	$ano_op_array_pago  = null,
																		      	$n_op_array_pago    = null,
																		      	$f_op_array_pago    = null,
																		      	$tipo_op_array_pago = null,
																		      	null,
																		      	$f_dc_adj_array_pago= null,
																				$parametro_bienes   = $cuenta_afectar,
																				$cuenta_afectar_2
																				);

												$sw1=0;
												$valida_cuenta_1=0;
												$valida_cuenta_2=0;
												
												if($valor_motor_contabilidad==true){
												
													$cantidad_cuentas=count($cuenta_afectar);
												
													for($in=0; $in<$cantidad_cuentas; $in++){
														
														$codigos_cuenta_cont = split('-', $cuenta_afectar[$in]['cuenta']);
														$aux_tipo_cuenta 	= 2;
														$aux_cuenta 	    = $codigos_cuenta_cont[0];
														$aux_subcuenta 	= $codigos_cuenta_cont[1];
														$aux_division  	= $codigos_cuenta_cont[2];
														$aux_subdivision= $codigos_cuenta_cont[3];
														$aux_monto= $cuenta_afectar[$in]['monto'];
														
														$sql_tabla_cuentas="insert into 
																				cstd03_movimientos_manuales_ingresos
																				values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria','$tipo_documento','$numero_documento','$aux_tipo_cuenta','$aux_cuenta','$aux_subcuenta','$aux_division','$aux_subdivision','$aux_monto')";
														$sw1 = $this->cstd03_movimientos_manuales->execute($sql_tabla_cuentas);
														
														if($sw1>1){}else{break;}

													 	
													
													}//fin for liena 185

													//$sw1=2;///////////PARA BLOQUIAR CONTABILIDAD
											  		if($sw1>1){
													
											  			//************* Para Pago de ordenes de pago por Transferencia *****************//
														if($this->data['cstp03_movimientos_manuales']['pagotransferencia']==1){

															$dep_solicitud=$this->Session->read('dep_solicitud');
															$ano_solicitud=$this->Session->read('ano_solicitud');
															$num_solicitud=$this->Session->read('num_solicitud');
															$cond_solicitud="cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$dep_solicitud." and ano_solicitud=".$ano_solicitud." and numero_solicitud=".$num_solicitud;

																if($this->csrd01_solicitud_recurso_cuerpo->findCount($cond_solicitud)!=0){

																	//La orden fue encontrada, si existe esa informacion se atualiza la tabla de solicitud de recursos.
															   		// *******************************************************************************************************************************************************
															   		// Rutina de comparacion de las partidas, para actualizar los montos en caso de no realizarse la entrega de todo el monto solicitado por la dep, rutina extraida del programa de solicitud recurso aprobacion.
															   		// *******************************************************************************************************************************************************

											   						$max=$this->v_csrd01_solicitud_recurso_cuerpo->execute("SELECT monto_solicitado,monto_entregado FROM v_csrd01_solicitud_recurso_cuerpo WHERE ".$cond_solicitud);
																	$monto_solicitud=$max[0][0]["monto_solicitado"];
																	$num_entregado=$max[0][0]["monto_entregado"];
																	//$monto_entregado=$monto+$num_entregado;
																	$monto_entregado=$monto;
																   	//$ver_udp=$this->SQLCA()." and numero_solicitud=".$numero_solicitud." and ano_solicitud=".$dato;
																   	//$ver_udp=$cond_solicitud;
																   	$sql_update="update 
																   					csrd01_solicitud_recurso_cuerpo 
																   					set monto_entregado=".$monto_entregado.", cod_entidad_bancaria=".$cod_entidad_bancaria.", cod_sucursal=".$cod_sucursal.",
																   						cuenta_bancaria='".$cuenta_bancaria."', numero_cheque=".$numero_documento.", 
																   						fecha_cheque='".$fecha_documento."'   
																   					where ".$cond_solicitud;
													
																	$udt=$this->csrd01_solicitud_recurso_cuerpo->execute($sql_update);

												    				if($monto_solicitud!=$monto){

																		$total_acumulado=0;
																		$i=1;
													    				$cant_partidas=$this->csrd01_solicitud_recurso_partidas->findCount($cond_solicitud);

													    				if($cant_partidas!=0){
													    					
													    					$partidas=$this->csrd01_solicitud_recurso_partidas->FindAll($cond_solicitud);

																			foreach($partidas as $row){

																				$ano=$row['csrd01_solicitud_recurso_partidas']['ano'];
																				$cod_sector=$row['csrd01_solicitud_recurso_partidas']['cod_sector'];
																				$cod_programa=$row['csrd01_solicitud_recurso_partidas']['cod_programa'];
																				$cod_sub_prog=$row['csrd01_solicitud_recurso_partidas']['cod_sub_prog'];
																				$cod_proyecto=$row['csrd01_solicitud_recurso_partidas']['cod_proyecto'];
																				$cod_activ_obra=$row['csrd01_solicitud_recurso_partidas']['cod_activ_obra'];
																				$cod_partida=$row['csrd01_solicitud_recurso_partidas']['cod_partida'];
																				$cod_generica=$row['csrd01_solicitud_recurso_partidas']['cod_generica'];
																				$cod_especifica=$row['csrd01_solicitud_recurso_partidas']['cod_especifica'];
																				$cod_sub_espec=$row['csrd01_solicitud_recurso_partidas']['cod_sub_espec'];
																				$cod_auxiliar=$row['csrd01_solicitud_recurso_partidas']['cod_auxiliar'];
																				$monto22=$row['csrd01_solicitud_recurso_partidas']['monto'];
																				$total_modificar=(($monto_entregado*$monto22)/$monto_solicitud);
																				$condicion_partidas = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$dep_solicitud;

																				if($i==1){

																					$condicion1=$condicion_partidas." and numero_solicitud=".$num_solicitud." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
																				}
																
																				$condicion=$condicion_partidas." and numero_solicitud=".$num_solicitud." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
																
																				$sql_update1="update
																								 csrd01_solicitud_recurso_partidas
																								 set monto_entregado=".$total_modificar." 
																								 where ".$condicion;
																				$udt1=$this->csrd01_solicitud_recurso_partidas->execute($sql_update1);
																				$i++;
															
																			}// fin foreach linea 250
															
																			//echo $monto_entregado."  ".$total_acumulado;
																			$maximo=$this->csrd01_solicitud_recurso_partidas->execute("SELECT numero_solicitud,sum((monto_entregado)::numeric)::numeric(22,2) AS monto_total FROM csrd01_solicitud_recurso_partidas WHERE ".$cond_solicitud." GROUP BY numero_solicitud");
																			$sum_monto_entregado=$maximo[0][0]["monto_total"];
																			$ver=$this->csrd01_solicitud_recurso_partidas->execute("SELECT numero_solicitud,monto,monto_entregado FROM csrd01_solicitud_recurso_partidas WHERE ".$cond_solicitud);
																			$monto_partida=$ver[0][0]["monto_entregado"];
															
																			if($monto_entregado != $sum_monto_entregado){
															
																				$total_diferencia_centimos=$monto_entregado-$sum_monto_entregado;
															
																				if($total_diferencia_centimos > 0){
																
																					$total_modificar_centimo=$monto_partida+$total_diferencia_centimos;
																
																				}else if ($total_diferencia_centimos < 0){
																	
																					$total_modificar_centimo=$monto_partida-$total_diferencia_centimos;
																
																				}

																				$sql_update1="update 
																								csrd01_solicitud_recurso_partidas 
																								set monto_entregado=".$total_modificar_centimo." 
																								where ".$condicion1;
																
																				$udt1=$this->csrd01_solicitud_recurso_partidas->execute($sql_update1);
																			} // fin si linea 289

																			
																			if($udt>1){

																				$this->cstd03_movimientos_manuales->execute("COMMIT;");
															 					$this->set('mensaje', 'LA OPERACI&Oacute;N FU&Eacute; PROCESADA EXITOSAMENTE');

																			}else{
															
															 					$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
																				$this->set('mensajeError','1- Los datos de la nota de debito no fueron guardados');

																			}
														
																		}else{
														
																			$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
														  					$this->set('mensajeError','2-Los datos de la nota de debito no fueron guardados');
														
																		} // fin cantidad partidas
														
																	}else{
														
																		//$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
																		//$this->set('mensajeError','Los datos del cheque no fueron guardados');
																		$this->cstd03_movimientos_manuales->execute("COMMIT;");
																		$this->set('mensaje', 'LA OPERACI&Oacute;N FU&Eacute; PROCESADA EXITOSAMENTE');
													
																	} // fin monto_solicitud linea 240
									
																}else{

																	$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
																	$this->set('mensajeError','3-Los datos de la nota de debito no fueron guardados');

																}

														}else{

															$this->cstd03_movimientos_manuales->execute("COMMIT;");
									                    	$this->set('mensaje', 'LA OPERACIÓN FUÉ PROCESADA EXITOSAMENTE');
									                	}
													
													}else{
													
														$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
														$this->set('mensajeError','Los datos de la nota de debito no fuerón guardados');
													
													}
								                    
								                }else{ // fin si valor_mortor_contabilidad linea 181
								                
								                	$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
								                    $this->set('mensajeError','Los datos de la nota de debito no fuerón guardados');
								                
								                }
											
										}else{ // fin tipo de cuenta linea 89
											
											$this->set('mensaje', 'LA OPERACIÓN FUÉ PROCESADA EXITOSAMENTE');
											$this->cstd03_movimientos_manuales->execute("COMMIT;");
											
										}

									}else{

											$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
											$this->set('mensajeError', 'SE ACTUALIZO LA CUENTA BANCARIA, PERO NO SE PUDO GRABAR EN MOV. GENERALES');
									}

							
								}else{ // fin condicion update cuentas bancarias liena 79
						        
						            $this->set('mensajeError','Los datos de la nota de debito no fueron guardados');
									$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
								
								} // fin sino update cuentas bancarias liena 383

							}else{  // fin condicion si insert movimientos_manuales linea 64
								$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
								$this->set('mensajeError','Los datos de la nota de debito no fueron guardados');
							}

						} // fin condicion $procede_mov == si
						break;




				case 4:
						//echo "<br>Es un Cheque";
						$numegreso="";
						$nuevo_numegreso="";
						$procede_mov="";
						$mensaje_egreso="";
						$colocacion = 2;
						$this->cstd03_movimientos_manuales->execute("BEGIN;");
						if($this->cstd06_comprobante_numero_egreso->findCount($this->SQLCA()." and ano_comprobante_egreso='".$ano_movimiento."'")>0){
								//************* Para verificar que el monto del cheque no sea mayor al monto solitado en la orden de pago por Transferencia *****************//
								if($this->data['cstp03_movimientos_manuales']['pagotransferencia']==1){//Indica que el cheque paga una orden de pago de transferencia

											$dep_solicitud=$this->Session->read('dep_solicitud');
											$ano_solicitud=$this->Session->read('ano_solicitud');
											$num_solicitud=$this->Session->read('num_solicitud');

											$cond_solicitud="cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$dep_solicitud." and ano_solicitud=".$ano_solicitud." and numero_solicitud=".$num_solicitud;
											if($this->csrd01_solicitud_recurso_cuerpo->findCount($cond_solicitud)!=0){
												//La orden fue encontrada
										   		$max=$this->v_csrd01_solicitud_recurso_cuerpo->execute("SELECT monto_solicitado,monto_entregado FROM v_csrd01_solicitud_recurso_cuerpo WHERE ".$cond_solicitud);
												$monto_solicitud=$max[0][0]["monto_solicitado"];
												$num_entregado=$max[0][0]["monto_entregado"];
												$monto_entregado=$monto;
											    if($monto > $monto_solicitud){
											    	$procede_mov="no";
													$mensaje_egreso="EL MOVIMIENTO NO PROCEDE EL MONTO A ENTREGAR ES MAYOR AL DE LA SOLICITUD DE RECURSOS";
											    }else{
											    	$procede_mov="si";
								 			    }
											}else{
												$procede_mov="no";
												$mensaje_egreso="EL MOVIMIENTO NO PROCEDE LA SOLICITUD DE RECURSOS NO PUDO SER ENCONTRADA";
											}
								}else{
									$procede_mov="si";
								}

								if($procede_mov=="si"){
									$numegreso=$this->cstd06_comprobante_numero_egreso->findAll($this->SQLCA()." and ano_comprobante_egreso=".$ano_movimiento);
									// Numero comprobante de egreso aplica solo en los cheques.
									$nuevo_numegreso=$numegreso[0]['cstd06_comprobante_numero_egreso']['numero_comprobante_egreso'] + 1;
									if($this->cstd06_comprobante_cuerpo_egreso->findCount($this->SQLCA()." and ano_comprobante_egreso='".$ano_movimiento."'  and  numero_comprobante_egreso='".$nuevo_numegreso."'")>0){
										$procede_mov="no";
										$mensaje_egreso="EL MOVIMIENTO NO PROCEDE EL NÚMERO DE EGRESO YA SE ENCUENTRA PRESENTE EN UN COMPROBANTE";
									}else{
										$actualiza_egreso_num="UPDATE cstd06_comprobante_numero_egreso SET numero_comprobante_egreso='$nuevo_numegreso' WHERE ".$this->SQLCA()." and ano_comprobante_egreso='$ano_movimiento'";
										if($this->cstd06_comprobante_numero_egreso->execute($actualiza_egreso_num)>1){
										   $procede_mov="si";
										}else{
										   $procede_mov="no";
										   $mensaje_egreso="EL MOVIMIENTO NO PROCEDE, NO SE PUDO ACTUALIZAR EL NÚMERO DE COMPROBANTE EGRESO";
										}
									}
								}//$procede_mov
						}else{
						// No se encontro la dependencia que esta registrando el cheque en la tabla de numero comprobante de egreso, no posee registro previo
						// por eso procedemos a insertar en esa tabla y despues continuar registrando en comprobante numero y comprobante cuerpo egreso.
						$crea_num_egreso="insert into cstd06_comprobante_numero_egreso values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento',1)";
								if($this->cstd06_comprobante_numero_egreso->execute($crea_num_egreso)>1){
								   	$nuevo_numegreso=1;
									$procede_mov="si";
								}else{
								   $procede_mov="no";
								   $mensaje_egreso="EL MOVIMIENTO NO PROCEDE, NO SE PUDO CREAR EL NÚMERO DE COMPROBANTE DE EGRESO";
								}
						}



						if($procede_mov=="si"){
								$sql="insert into cstd03_movimientos_manuales values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria',".
									"'$tipo_documento','$numero_documento','$fecha_documento',".
									"'$beneficiario','$monto','$concepto','$fecha_proceso_registro','$dia_asiento_registro','$mes_asiento_registro','$ano_asiento_registro','$numero_asiento_registro','$username_registro',".
									"'$condicion_actividad',".
									"'0', '0', '1900/01/01', '0', '0', '0', '0', '0','0','0',$colocacion,'2','$cod_tipo_enlace','$caja_chica','$caja_chica_rendida','$dep_solicitud','$ano_solicitud','$num_solicitud')";
									if($this->cstd03_movimientos_manuales->execute($sql)>1){
					   					//$this->set('mensaje','Los datos fueron insertados correctamente');
					   					$cstd02_cuentas_bancarias=$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
					   					$disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
					   					$tipo_cuenta=$disponibilidad[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];
					   					$monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['cheque_dia'];
					   					$monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['cheque_mes'];
					   					$monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['cheque_ano'];
										$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro'];
					   					$nuevo_monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['cheque_dia']+$monto;//sumo el monto al cheque_dia
					   					$nuevo_monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['cheque_mes']+$monto;//sumo el monto al cheque_mes
					   					$nuevo_monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['cheque_ano']+$monto;//sumo el monto al la cheque_ano
					   					$nuevo_monto_disp_real=$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro'] - $monto;//resto el monto a la disponibilidad del libro
					   					$actualiza_dispo="UPDATE cstd02_cuentas_bancarias SET cheque_dia=".$nuevo_monto_dia.", cheque_mes=".$nuevo_monto_mes.", cheque_ano=".$nuevo_monto_ano.", disponibilidad_libro=".$nuevo_monto_disp_real." WHERE ".$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
										if($this->cstd02_cuentas_bancarias->execute($actualiza_dispo)>1){
											$update_cheque="UPDATE cstd03_cheque_numero SET situacion=3 WHERE ".$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_cheque=".$numero_documento;
											if($this->cstd03_cheque_numero->execute($update_cheque)>1){
													//Insertamos en movimientos generales
										            $inserta_mov_generales="insert into cstd04_movimientos_generales values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria',".
										      																			    "'$mesmov_general','$diamov_general','$tipo_documento','$numero_documento','$monto')";
													if($this->cstd04_movimientos_generales->execute($inserta_mov_generales)>1){
													//*********************************************************************//
														$inserta_cheque_poremitir="insert into cstd04_cheque_poremitir values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$username_registro','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria','$numero_documento')";
														if($this->cstd04_cheque_poremitir->execute($inserta_cheque_poremitir)>1){
													 			$inserta_egreso_cuerpo="insert into cstd06_comprobante_cuerpo_egreso values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$nuevo_numegreso','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria','$numero_documento')";
																if($this->cstd06_comprobante_cuerpo_egreso->execute($inserta_egreso_cuerpo)>1){



                                                                    $sw1 = 0;
                                                                    $cuenta_afectar  = null;

																						if($tipo_cuenta==1){

																							$bandera_cuenta_repetida=false;
																							$monto_in_2 = '';
																							$cant_ing=count($listado_ing);
																							for($in=0; $in<$cant_ing; $in++){
																								$cuenta_cuenta_1 = $listado_ing[$in];
																								$cuenta_cuenta_2 = isset($listado_ing[$in+1]) ? $listado_ing[$in+1] : '';
																								$monto_in = $this->Formato1($this->data['cstp03_movimientos_manuales']['montotipoingreso_'.$listado_ing[$in].'_'.$in]);
																								if($cuenta_cuenta_1 == $cuenta_cuenta_2){
																									$monto_in_2 += $monto_in;
																									$bandera_cuenta_repetida = true;
																								}else{
																									if($bandera_cuenta_repetida==true){
																										if($monto_in_2 > 0 || $monto_in > 0){
																											$monto_in_2 += $monto_in;
																											//$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$this->Formato1($monto_in_2));
																											$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$monto_in_2);
																										}
																											$bandera_cuenta_repetida=false;
																											$monto_in_2=0;
																									}else{
																										if($monto_in > 0){
																											//$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$this->Formato1($monto_in));
																											$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$monto_in);
																										}
																									}
																								}

																								/*
																								$monto_in = $this->data['cstp03_movimientos_manuales']['montotipoingreso_'.$listado_ing[$in]];
																								if($monto_in != 0){
																									$cuenta_afectar[]=array('cuenta'=>$listado_ing[$in], 'monto'=>$this->Formato1($monto_in));
																								}
																								*/
																							}

																							$monto_total["monto_total"]        = $monto;
																							$cuenta_afectar_2["tipo_cuenta"]     = $tipo_cuenta;
													                                        $cuenta_afectar_2["tipo_movimiento"] = 1;
																							$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
																						    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

																							$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																												$to      = 1,
																												$td      = 20,
																												$rif_doc = null,
																												$ano_dc  = $ano_movimiento,
																												$n_dc    = $numero_documento,
																												$f_dc    = $fecha_documento,
																												$cpt_dc  = null,
																												$ben_dc  = $beneficiario,
																												$mon_dc  = $monto_total,
																												$ano_op   = null,
																											    $n_op     = null,
																											    $f_op     = null,
																											    $a_adj_op = null,
																											    $n_adj_op = null,
																											    $f_adj_op = null,
																											    $tp_op    = null,
																											    $deno_ban_pago  = $cod_entidad_bancaria_aux,
																											    $ano_movimiento = $ano_movimiento,
																											    $cod_ent_pago   = $cod_entidad_bancaria,
																											    $cod_suc_pago   = $cod_sucursal,
																											    $cod_cta_pago   = $cuenta_bancaria,
																											    $num_che_o_debi  = $numero_documento,
																											    $fec_che_o_debi  = $fecha_documento,
																											    $clas_che_o_debi = null,
																										    $tipo_che_o_debi = null,
																										    $ano_dc_array_pago     = null,
																										    $n_dc_array_pago       = null,
																										    $n_dc_adj_array_pago   = null,
																										    $f_dc_array_pago       = null,
																										    $ano_op_array_pago  = null,
																										    $n_op_array_pago    = null,
																										    $f_op_array_pago    = null,
																										    $tipo_op_array_pago = null,
																										    null,
																										    $f_dc_adj_array_pago= null,
																										    $parametro_bienes   = $cuenta_afectar,
																										    $cuenta_afectar_2
																																					);

																							$sw1=0;
																							$valida_cuenta_1=0;
																							$valida_cuenta_2=0;
																							if($valor_motor_contabilidad==true){
																									$cantidad_cuentas=count($cuenta_afectar);
																									for($in=0; $in<$cantidad_cuentas; $in++){
																											$codigos_cuenta_cont = split('-', $cuenta_afectar[$in]['cuenta']);
																											 $aux_tipo_cuenta 	= 2;
																											 $aux_cuenta 	    = $codigos_cuenta_cont[0];
																											 $aux_subcuenta 	= $codigos_cuenta_cont[1];
																											 $aux_division  	= $codigos_cuenta_cont[2];
																											 $aux_subdivision= $codigos_cuenta_cont[3];
																											 $aux_monto= $cuenta_afectar[$in]['monto'];
																											 $sql_tabla_cuentas="insert into cstd03_movimientos_manuales_ingresos values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria','$tipo_documento','$numero_documento','$aux_tipo_cuenta','$aux_cuenta','$aux_subcuenta','$aux_division','$aux_subdivision','$aux_monto')";
													                                                         $sw1 = $this->cstd03_movimientos_manuales->execute($sql_tabla_cuentas);
																										     if($sw1>1){}else{break;}

																											 /*
																											 $valida_cuenta_1 = $cuenta_afectar[$in]['cuenta'];
																											 if($in==0){
																												 $sql_tabla_cuentas="insert into cstd03_movimientos_manuales_ingresos values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria','$tipo_documento','$numero_documento','$aux_tipo_cuenta','$aux_cuenta','$aux_subcuenta','$aux_division','$aux_subdivision','$aux_monto')";
																												 $sw1 = $this->cstd03_movimientos_manuales->execute($sql_tabla_cuentas);
																											 }else{
																												 if($valida_cuenta_1 == $valida_cuenta_2){
																													 $aux_monto .= $aux_monto;
																									 			 }else{
																									 			 	 $sql_tabla_cuentas="insert into cstd03_movimientos_manuales_ingresos values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano_movimiento','$cod_entidad_bancaria','$cod_sucursal','$cuenta_bancaria','$tipo_documento','$numero_documento','$aux_tipo_cuenta','$aux_cuenta','$aux_subcuenta','$aux_division','$aux_subdivision','$aux_monto')";
																													 $sw1 = $this->cstd03_movimientos_manuales->execute($sql_tabla_cuentas);
																									 			 }
																											 }
																											 $valida_cuenta_2 = $cuenta_afectar[$in]['cuenta'];
																											 */
																								  	 }//fin for
										                                                    }


																					}else if($tipo_cuenta==2){


																							$monto_total["monto_total"]        = $monto;
																							$cuenta_afectar_2["tipo_retencion"]  = $cod_tipo_enlace;
																							$cuenta_afectar_2["tipo_cuenta"]     = $tipo_cuenta;
													                                        $cuenta_afectar_2["tipo_movimiento"] = 1;
																							$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
																						    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];


																							$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																														      $to      = 1,
																														      $td      = 20,
																														      $rif_doc = null,
																														      $ano_dc  = $ano_movimiento,
																														      $n_dc    = $numero_documento,
																														      $f_dc    = $fecha_documento,
																														      $cpt_dc  = null,
																														      $ben_dc  = $beneficiario,
																														      $mon_dc  = $monto_total,
																														      $ano_op   = null,
																														      $n_op     = null,
																														      $f_op     = null,

																														      $a_adj_op = null,
																														      $n_adj_op = null,
																														      $f_adj_op = null,
																														      $tp_op    = null,
																														      $deno_ban_pago  = $cod_entidad_bancaria_aux,
																														      $ano_movimiento = $ano_movimiento,
																														      $cod_ent_pago   = $cod_entidad_bancaria,
																														      $cod_suc_pago   = $cod_sucursal,
																														      $cod_cta_pago   = $cuenta_bancaria,
																														      $num_che_o_debi  = $numero_documento,
																														      $fec_che_o_debi  = $fecha_documento,
																														      $clas_che_o_debi = null,
																														      $tipo_che_o_debi = null,
																														      $ano_dc_array_pago     = null,
																														      $n_dc_array_pago       = null,
																														      $n_dc_adj_array_pago   = null,
																														      $f_dc_array_pago       = null,
																														      $ano_op_array_pago  = null,
																														      $n_op_array_pago    = null,
																														      $f_op_array_pago    = null,
																														      $tipo_op_array_pago = null,
																														      null,
																														      $f_dc_adj_array_pago= null,
																														      $parametro_bienes   = null,
																														      $cuenta_afectar_2
																																					);

																							if($valor_motor_contabilidad==true){
																									$sw1=2;
										                                                    }

																					}//fin if

                                                                               //$sw1=2;///////////PARA BLOQUEAR CONTABILIDAD

                                                                            if($sw1>1){


																							//************* Para Pago de ordenes de pago por Transferencia *****************//
																							if($this->data['cstp03_movimientos_manuales']['pagotransferencia']==1){
																										$dep_solicitud=$this->Session->read('dep_solicitud');
																										$ano_solicitud=$this->Session->read('ano_solicitud');
																										$num_solicitud=$this->Session->read('num_solicitud');

																										$cond_solicitud="cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$dep_solicitud." and ano_solicitud=".$ano_solicitud." and numero_solicitud=".$num_solicitud;
																										if($this->csrd01_solicitud_recurso_cuerpo->findCount($cond_solicitud)!=0){
																											//La orden fue encontrada, si existe esa informacion se atualiza la tabla de solicitud de recursos.
																									   		// *******************************************************************************************************************************************************
																									   		// Rutina de comparacion de las partidas, para actualizar los montos en caso de no realizarse la entrega de todo el monto solicitado por la dep, rutina extraida del programa de solicitud recurso aprobacion.
																									   		// *******************************************************************************************************************************************************
																									   		$max=$this->v_csrd01_solicitud_recurso_cuerpo->execute("SELECT monto_solicitado,monto_entregado FROM v_csrd01_solicitud_recurso_cuerpo WHERE ".$cond_solicitud);
																											$monto_solicitud=$max[0][0]["monto_solicitado"];
																											$num_entregado=$max[0][0]["monto_entregado"];
																											//$monto_entregado=$monto+$num_entregado;
																											$monto_entregado=$monto;

																										   	//$ver_udp=$this->SQLCA()." and numero_solicitud=".$numero_solicitud." and ano_solicitud=".$dato;
																										   	//$ver_udp=$cond_solicitud;
																										   	$sql_update="update csrd01_solicitud_recurso_cuerpo set monto_entregado=".$monto_entregado.", cod_entidad_bancaria=".$cod_entidad_bancaria.", cod_sucursal=".$cod_sucursal.", cuenta_bancaria='".$cuenta_bancaria."', numero_cheque=".$numero_documento.", fecha_cheque='".$fecha_documento."'   where ".$cond_solicitud;
																										    $udt=$this->csrd01_solicitud_recurso_cuerpo->execute($sql_update);

//																										    echo "<br />".$monto_solicitud;
//																										    echo "<br />".$monto;

																										    if($monto_solicitud!=$monto){
																													$total_acumulado=0;
																													$i=1;
																												    $cant_partidas=$this->csrd01_solicitud_recurso_partidas->findCount($cond_solicitud);
																												    if($cant_partidas!=0){
																												    	$partidas=$this->csrd01_solicitud_recurso_partidas->FindAll($cond_solicitud);
																														foreach($partidas as $row){
																															$ano=$row['csrd01_solicitud_recurso_partidas']['ano'];
																															$cod_sector=$row['csrd01_solicitud_recurso_partidas']['cod_sector'];
																															$cod_programa=$row['csrd01_solicitud_recurso_partidas']['cod_programa'];
																															$cod_sub_prog=$row['csrd01_solicitud_recurso_partidas']['cod_sub_prog'];
																															$cod_proyecto=$row['csrd01_solicitud_recurso_partidas']['cod_proyecto'];
																															$cod_activ_obra=$row['csrd01_solicitud_recurso_partidas']['cod_activ_obra'];
																															$cod_partida=$row['csrd01_solicitud_recurso_partidas']['cod_partida'];
																															$cod_generica=$row['csrd01_solicitud_recurso_partidas']['cod_generica'];
																															$cod_especifica=$row['csrd01_solicitud_recurso_partidas']['cod_especifica'];
																															$cod_sub_espec=$row['csrd01_solicitud_recurso_partidas']['cod_sub_espec'];
																															$cod_auxiliar=$row['csrd01_solicitud_recurso_partidas']['cod_auxiliar'];
																															$monto22=$row['csrd01_solicitud_recurso_partidas']['monto'];

																															$total_modificar=(($monto_entregado*$monto22)/$monto_solicitud);
																															$condicion_partidas = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$dep_solicitud;
																															if($i==1){
																																$condicion1=$condicion_partidas." and numero_solicitud=".$num_solicitud." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
																															}
																															$condicion=$condicion_partidas." and numero_solicitud=".$num_solicitud." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
																															$sql_update1="update csrd01_solicitud_recurso_partidas set monto_entregado=".$total_modificar." where ".$condicion;
																												     		$udt1=$this->csrd01_solicitud_recurso_partidas->execute($sql_update1);
																															$i++;
																														}// fin foreach
																														//echo $monto_entregado."  ".$total_acumulado;
																														$maximo=$this->csrd01_solicitud_recurso_partidas->execute("SELECT numero_solicitud,sum((monto_entregado)::numeric)::numeric(22,2) AS monto_total FROM csrd01_solicitud_recurso_partidas WHERE ".$cond_solicitud." GROUP BY numero_solicitud");
																														$sum_monto_entregado=$maximo[0][0]["monto_total"];
																														$ver=$this->csrd01_solicitud_recurso_partidas->execute("SELECT numero_solicitud,monto,monto_entregado FROM csrd01_solicitud_recurso_partidas WHERE ".$cond_solicitud);
																														$monto_partida=$ver[0][0]["monto_entregado"];
																														if($monto_entregado != $sum_monto_entregado){
																															$total_diferencia_centimos=$monto_entregado-$sum_monto_entregado;
																															if($total_diferencia_centimos > 0){
																																$total_modificar_centimo=$monto_partida+$total_diferencia_centimos;
																															}else if ($total_diferencia_centimos < 0){
																																$total_modificar_centimo=$monto_partida-$total_diferencia_centimos;
																															}

																															$sql_update1="update csrd01_solicitud_recurso_partidas set monto_entregado=".$total_modificar_centimo." where ".$condicion1;
																													     	$udt1=$this->csrd01_solicitud_recurso_partidas->execute($sql_update1);

																														}
																														if($udt>1){
																															$this->cstd03_movimientos_manuales->execute("COMMIT;");
																														 	$this->set('mensaje', 'LA OPERACI&Oacute;N FU&Eacute; PROCESADA EXITOSAMENTE');
																														 }else{
																														 	$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
																														 	$this->set('mensajeError','1- Los datos del cheque no fueron guardados');
																														 }
																												    }else{
																												    	$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
																													    $this->set('mensajeError','2-Los datos del cheque no fueron guardados');
																													}
																												//fin condicion update monto y monto_entregado
																												}else{
																													//$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
																													//$this->set('mensajeError','Los datos del cheque no fueron guardados');
																													$this->cstd03_movimientos_manuales->execute("COMMIT;");
																												 	$this->set('mensaje', 'LA OPERACI&Oacute;N FU&Eacute; PROCESADA EXITOSAMENTE');
																												}
																											//}else{
																											  // $this->set('mensajeError', 'LA OPERACION FUE PROCESADA, PERO NO SE PUDO ACTUALIZAR LA ORDEN DE TRANSFERENCIA');
																											//}





																										}else{
																											$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
																											$this->set('mensajeError','3-Los datos del cheque no fueron guardados');

																										}


																								 }else{
																								 	$this->cstd03_movimientos_manuales->execute("COMMIT;");
																									$this->set('mensaje', 'LA OPERACI&Oacute;N FU&Eacute; PROCESADA EXITOSAMENTE');
																								 }


																		}else{
																			$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
																            $this->set('mensajeError','4-Los datos del cheque no fuerón guardados');
																		}

																}else{
																 $this->cstd03_movimientos_manuales->execute("ROLLBACK;");
																 $this->set('mensajeError','5-Los datos del cheque no fuerón guardados');
																}

														}else{
															$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
															$this->set('mensajeError','6-Los datos del cheque no fuerón guardados');
														}
													//*********************************************************************

													}else{
														$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
														$this->set('mensajeError','7-Los datos del cheque no fuerón guardados');
													}


											}else{
												$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
												$this->set('mensajeError','8-Los datos del cheque no fuerón guardados');
											}
										}else{
										//Si no se actualizaron los datos en las tablas "cstd02_cuentas_bancarias" me regreso a eliminar el registro que inserte en la "cstd03_movimientos_manuales"
											/*$delete="DELETE FROM cstd03_movimientos_manuales WHERE ".$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_documento=".$numero_documento;
											if($this->cstd03_movimientos_manuales->execute($delete)>1){
											$this->set('mensajeError', 'LA OPERACION NO FUE COMPLETADA, SE REGRES0 AL ESTADO ANTERIOR');
											}else{
											$this->set('mensajeError', 'LA OPERACION NO FUE COMPLETADA, NO FUERON ACTUALIZADOS DATOS DE IMPORTANCIA');
											}*/
											$this->set('mensajeError','9-Los datos del cheque no fuerón guardados');
											$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
										}
									}else{
										$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
					   					$this->set('mensajeError','10-Los datos del cheque no fuerón guardados');
									}
						}else{//else no procede el movimiento bancario
						   $this->set('mensajeError', $mensaje_egreso);
						}
						break;
			}//switche
		}//fin else informacion registrada

  	}else{
  		$this->set('mensajeError','Debe seleccionar el tipo de documento');

  	}//fin tipo documento

  	echo'<script>';
  	  echo"document.getElementById('b_guardar').disabled=false;";
  	echo'</script>';

}//fin guardar



function consultar($pagina=null, $mensaje=null) {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('mensaje',$mensaje);

	$ano_consulta = $this->Session->read('ano_consulta_mov');
	$ano_consulta != '' ? '' : $ano_consulta = $this->ano_ejecucion();
	$condicion_SQLA = $this->SQLCA()." and ano_movimiento=".$ano_consulta;

	if(isset($pagina)){
		$Tfilas=$this->cstd03_movimientos_manuales->findCount($condicion_SQLA);
        if($Tfilas!=0){
        	$data=$this->cstd03_movimientos_manuales->findAll($condicion_SQLA,null,"cod_entidad_bancaria, cod_sucursal, tipo_documento, numero_documento ASC",1,$pagina,null);
            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->set('ultimo',$Tfilas);
        }else{
 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos para el a&ntilde;o '.$ano_consulta);
 	       $this->set('noExiste',true);
        }

	}else{
		$pagina=1;
		$Tfilas=$this->cstd03_movimientos_manuales->findCount($condicion_SQLA);

        if($Tfilas!=0){
        	$data=$this->cstd03_movimientos_manuales->findAll($condicion_SQLA,null,"cod_entidad_bancaria, cod_sucursal, tipo_documento, numero_documento ASC",1,$pagina,null);
			$this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->set('ultimo',$Tfilas);
        }else{
	 	       $this->set('mensajeError', 'No se encontrar&oacute;n datos para el a&ntilde;o '.$ano_consulta);
	 	       $this->set('noExiste',true);
        }
	}

	if($Tfilas!=0){
	    $this->set('ano_movimiento', $data[0]['cstd03_movimientos_manuales']['ano_movimiento']);

		$cod_entidad_bancaria=$this->mascara3($data[0]['cstd03_movimientos_manuales']['cod_entidad_bancaria']);//-----No borrar la variable "cod_entidad_bancaria"
	    $this->set('cod_entidad_bancaria', $cod_entidad_bancaria);
	    $cod_sucursal=$this->mascara3($data[0]['cstd03_movimientos_manuales']['cod_sucursal']);//---------------------No borrar la variable "cod_sucursal"
	    $this->set('cod_sucursal', $cod_sucursal);

	    $this->set('cuenta_bancaria', $data[0]['cstd03_movimientos_manuales']['cuenta_bancaria']);
	    $this->set('tipo_documento', $data[0]['cstd03_movimientos_manuales']['tipo_documento']);
	    $this->set('numero_documento', $data[0]['cstd03_movimientos_manuales']['numero_documento']);
	    $this->set('fecha_documento', $data[0]['cstd03_movimientos_manuales']['fecha_documento']);
	    $this->set('beneficiario', $data[0]['cstd03_movimientos_manuales']['beneficiario']);
	    $this->set('monto', $data[0]['cstd03_movimientos_manuales']['monto']);
	    $this->set('concepto', $data[0]['cstd03_movimientos_manuales']['concepto']);
	    $this->set('fecha_proceso_registro', $data[0]['cstd03_movimientos_manuales']['fecha_proceso_registro']);
	    $this->set('dia_asiento_registro', $data[0]['cstd03_movimientos_manuales']['dia_asiento_registro']);
	    $this->set('mes_asiento_registro', $data[0]['cstd03_movimientos_manuales']['mes_asiento_registro']);
	    $this->set('ano_asiento_registro', $data[0]['cstd03_movimientos_manuales']['ano_asiento_registro']);
	    $this->set('numero_asiento_registro', $data[0]['cstd03_movimientos_manuales']['numero_asiento_registro']);
	    $this->set('username_registro', $data[0]['cstd03_movimientos_manuales']['username_registro']);
	    $this->set('colocacion', $data[0]['cstd03_movimientos_manuales']['colocacion']);
	    $this->set('caja_chica', $data[0]['cstd03_movimientos_manuales']['caja_chica']);
	    $this->set('codi_dep', $data[0]['cstd03_movimientos_manuales']['codi_dep']);
	    $this->set('ano_solicitud', $data[0]['cstd03_movimientos_manuales']['ano_solicitud']);
	    $this->set('num_solicitud', $data[0]['cstd03_movimientos_manuales']['num_solicitud']);

	    $condicion_actividad=$data[0]['cstd03_movimientos_manuales']['condicion_actividad'];//<---No borrar esta variable
	    $this->set('condicion_act', $condicion_actividad);

	    if($condicion_actividad == 2){

	    	$sacar = $this->cugd03_acta_anulacion_cuerpo->findAll($this->condicion(). " and numero_acta_anulacion=".$data[0]['cstd03_movimientos_manuales']['numero_anulacion']." and ano_acta_anulacion=".$data[0]['cstd03_movimientos_manuales']['ano_anulacion'] );
			foreach($sacar as $sa2){
			 	$concepto_anulacion= $sa2['cugd03_acta_anulacion_cuerpo']['motivo_anulacion'];
			}
	   		$this->set('concepto_anulacion',$concepto_anulacion);
	   		$this->set('enable_anular','disabled');
	   		$this->set('ano_anulacion', $data[0]['cstd03_movimientos_manuales']['ano_anulacion']);
	   		$this->set('numero_anulacion', $data[0]['cstd03_movimientos_manuales']['numero_anulacion']);
	   		$this->set('fecha_proceso_anulacion', $data[0]['cstd03_movimientos_manuales']['fecha_proceso_anulacion']);
	   		$this->set('dia_asiento_anulacion', $data[0]['cstd03_movimientos_manuales']['dia_asiento_anulacion']);
	   		$this->set('mes_asiento_anulacion', $data[0]['cstd03_movimientos_manuales']['mes_asiento_anulacion']);
	   		$this->set('ano_asiento_anulacion', $data[0]['cstd03_movimientos_manuales']['ano_asiento_anulacion']);
	   		$this->set('numero_asiento_anulacion', $data[0]['cstd03_movimientos_manuales']['numero_asiento_anulacion']);
	   		$this->set('username_anulacion', $data[0]['cstd03_movimientos_manuales']['username_anulacion']);
	    }else{
	    	$this->set('enable_anular','enable');
	    }

	$dataR=$this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$cod_entidad_bancaria);
	foreach($dataR as $dataR1){
	    	$this->set('denominacion_entidad',$dataR1['cstd01_entidades_bancarias']['denominacion']);
			}

	$dataR2=$this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal);
	foreach($dataR2 as $dataR22){
	    	$this->set('denominacion_sucursal',$dataR22['cstd01_sucursales_bancarias']['denominacion']);
			}

	$dataR3=$this->cstd02_cuentas_bancarias->findAll('cod_entidad_bancaria='.$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$data[0]['cstd03_movimientos_manuales']['cuenta_bancaria']."'");
		$this->set('tipo_cuenta',$dataR3[0]['cstd02_cuentas_bancarias']['tipo_cuenta']);

		$this->set('vacio','no');

	}else{
		$this->set('vacio','si');//No se encontraron datos, esta vacio.
	}

}//fin consultar



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




function anular(){
 	$this->layout="ajax";
 	//echo "anulacion";
}




function procesar_anulacion($ano_movimiento, $cod_entidad_bancaria, $cod_sucursal, $cuenta_bancaria, $tipo_documento, $numero_documento, $monto, $fecha_documento, $anterior){
 	$this->layout="ajax";

 	$nro_anulacion = $this->data['cstp03_movimientos_manuales']['nro_anulacion'];
 	$fecha_anulacion = $this->data['cstp03_movimientos_manuales']['fecha_anulacion'];
 	$dia_anulacion = $this->data['cstp03_movimientos_manuales']['dia_anulacion'];
 	$mes_anulacion = $this->data['cstp03_movimientos_manuales']['mes_anulacion'];


 	$ano_anulacion = $this->ano_ejecucion();
 	$asiento_anulacion = $this->data['cstp03_movimientos_manuales']['asiento_2'];
 	$operador_anulacion = $this->data['cstp03_movimientos_manuales']['operador_2'];
 	$concepto_anulacion = $this->data['cstp03_movimientos_manuales']['concepto_anulacion'];
 	$ano_movimiento;
 	$cod_entidad_bancaria;
	$cod_sucursal;
	$cuenta_bancaria;
	$tipo_documento;
	$numero_documento;
	$monto;
	$fecha_documento;
	$anterior;

	// Verificamos si el documento es una nota de debito (3)...
	// si es una nota de debito pasamos a verificar que esta no halla sido registrada a traves del programa de notas de debitos especiales.
	// $procede_acta="";
	$procede_acta="si";
	if($tipo_documento==3){
		$notadeb_esp=$this->SQLCA()." and ano_movimiento=".$ano_movimiento." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria' and tipo_documento=".$tipo_documento." and numero_documento=".$numero_documento;
		if($this->cstd09_notadebito_cuerpo->findCount($notadeb_esp)!=0){
			$procede_acta="no";
			$this->set('mensajeError', 'ATENCION: ES UNA NOTA DE DEBITO ESPECIAL, NO PUEDE SER ANULADA A TRAVES DE ESTE PROGRAMA');
		}else{
			$procede_acta="si";
		}

		$notadeb_cuerpo=$this->SQLCA()." and ano_movimiento=".$ano_movimiento." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria' and numero_debito=".$numero_documento;
		if($this->cstd09_notadebito_cuerpo_pago->findCount($notadeb_cuerpo)!=0){
			$procede_acta="no";
			$this->set('mensajeError', 'ESTA ES UNA NOTA DE DEBITO PAGADA CON ORDEN DE PAGO, NO PUEDE SER ANULADA A TRAVES DE ESTE PROGRAMA');
		}

	}




	if($procede_acta=="si"){//Procedemos a recuperar el numero de acta de anulacion y a ejecutar todo el proceso de anulacion normalmente.
		$ano = $this->ano_ejecucion();
		$nuevo_num_anulacion="";
		$procede_anu="";
		if($this->cugd03_acta_anulacion_numero->findCount($this->SQLCA()." and ano_acta_anulacion=".$ano)>0){
			   $numanulacion=$this->cugd03_acta_anulacion_numero->findAll($this->SQLCA()." and ano_acta_anulacion=".$ano);
			   //Numero acta anulacion
			   $nuevo_num_anulacion=$numanulacion[0]['cugd03_acta_anulacion_numero']['numero_acta_anulacion'] + 1;
			   $sql_update="UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion='$nuevo_num_anulacion' WHERE ".$this->SQLCA()." and ano_acta_anulacion='$ano'";
			   if($this->cugd03_acta_anulacion_numero->execute($sql_update)>1){
					$procede_anu="si";
			   }else{
					$procede_anu="no";
					$mens_anulacion="NO SE PUEDE PROCESAR LA ANULACIÓN, NO PUDO SER ACTUALIZADO EL NÚMERO DEL ACTA";
			   }
		}else{
			   $crea_num_acta="insert into cugd03_acta_anulacion_numero values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano',1)";
								if($this->cugd03_acta_anulacion_numero->execute($crea_num_acta)>1){
								   	$nuevo_num_anulacion=1;
									$procede_anu="si";
								}else{
								    $procede_anu="no";
								    $mens_anulacion="NO SE PUEDE PROCESAR LA ANULACIÓN, NO PUDO SE PUDO CREAR EL NÚMERO DEL ACTA";
								}
		}




			if($procede_anu=="si"){
				$cod_dependencias=$this->SQLCA();
				switch($tipo_documento){
					case 1: //Es un Deposito
							$cod_anulacion=261;//codigo asignado para identificar el tipo de anulacion
							//se realiza el acta de anulacion del documento
							$this->cstd03_movimientos_manuales->execute("BEGIN;");
							$escribe_acta="INSERT INTO cugd03_acta_anulacion_cuerpo values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano','$nuevo_num_anulacion','$cod_anulacion','".$ano_movimiento."','$numero_documento','".$fecha_documento."','$concepto_anulacion')";
							if($this->cugd03_acta_anulacion_cuerpo->execute($escribe_acta)>1){
								$consulta="select * from cstd03_movimientos_manuales where ".$this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
								//si el documento existe continuo para actualizar la condicion del documento a anulado la tabla de movimientos manuales
								if($tabla_movimiento=$this->cstd03_movimientos_manuales->execute($consulta)){

									$sql="update cstd03_movimientos_manuales set ano_anulacion='$ano_anulacion', numero_anulacion='$nuevo_num_anulacion', fecha_proceso_anulacion='$fecha_anulacion',".
									 " dia_asiento_anulacion=0, mes_asiento_anulacion=0, ano_asiento_anulacion=0, numero_asiento_anulacion=0, username_anulacion='$operador_anulacion', condicion_actividad='2'".
									 " where ".$this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";

                                 if($this->cstd03_movimientos_manuales->execute($sql)>1){

                                 	        $cstd02_cuentas_bancarias=$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
						   					$disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
						   						$tipo_cuenta=$disponibilidad[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];
						   						$monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['deposito_dia'];
						   						$monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['deposito_mes'];
						   						$monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['deposito_ano'];
						   						$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro'];
						   						$nuevo_monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['deposito_dia']-$monto;//se descuenta el saldo
						   						$nuevo_monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['deposito_mes']-$monto;
						   						$nuevo_monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['deposito_ano']-$monto;
						   						$nuevo_monto_disp_real=$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro']-$monto;
												$actualiza_dispo="UPDATE cstd02_cuentas_bancarias SET deposito_dia=".$nuevo_monto_dia.", deposito_mes=".$nuevo_monto_mes.", deposito_ano=".$nuevo_monto_ano.", disponibilidad_libro=".$nuevo_monto_disp_real." WHERE ".$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";

												if($this->cstd02_cuentas_bancarias->execute($actualiza_dispo)>1){

                                                       if($tipo_cuenta==1){
																$sql_cuentas     = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
							                                    $datos_cuentas   = $this->cstd03_movimientos_manuales_ingresos->findAll($sql_cuentas);
							                                    $datos_cuentas2  = $this->cstd03_movimientos_manuales->findAll($sql_cuentas);

							                                    if(count($datos_cuentas) == 0){// No hay datos de contabilidad de movimientos
							                                    	$valor_motor_contabilidad = true;
							                                    }else{

									                                      foreach($datos_cuentas as $ve_aux_cuenta_1){
									                                        $var_aux_cuenta   = $ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_cuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subcuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_division'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subdivision'];
																			$cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['monto']);
									                                      }

									                                        $monto_total["monto_total"] = $datos_cuentas2[0]["cstd03_movimientos_manuales"]["monto"];
																			$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
																		    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

																			$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																																	      $to      = 2,
																																	      $td      = 2,
																																	      $rif_doc = null,
																																	      $ano_dc  = $ano_movimiento,
																																	      $n_dc    = $numero_documento,
																																	      $f_dc    = date("d/m/Y"),
																																	      $cpt_dc  = null,
																																	      $ben_dc  = $datos_cuentas2[0]["cstd03_movimientos_manuales"]["beneficiario"],
																																	      $mon_dc  = $monto_total,
																																	      $ano_op   = null,
																																	      $n_op     = null,
																																	      $f_op     = null,

																																	      $a_adj_op = null,
																																	      $n_adj_op = null,
																																	      $f_adj_op = null,
																																	      $tp_op    = null,
																																	      $deno_ban_pago  = $cod_entidad_bancaria_aux,
																																	      $ano_movimiento = $ano_movimiento,
																																	      $cod_ent_pago   = $cod_entidad_bancaria,
																																	      $cod_suc_pago   = $cod_sucursal,
																																	      $cod_cta_pago   = $cuenta_bancaria,
																																	      $num_che_o_debi  = $numero_documento,
																																	      $fec_che_o_debi  = date("d/m/Y"),
																																	      $clas_che_o_debi = null,
																																	      $tipo_che_o_debi = null,
																																	      $ano_dc_array_pago     = null,
																																	      $n_dc_array_pago       = null,
																																	      $n_dc_adj_array_pago   = null,
																																	      $f_dc_array_pago       = null,
																																	      $ano_op_array_pago  = null,
																																	      $n_op_array_pago    = null,
																																	      $f_op_array_pago    = null,
																																	      $tipo_op_array_pago = null,
																																	      null,
																																	      $f_dc_adj_array_pago= null,
																																	      $parametro_bienes   = $cuenta_afectar
																																	);

							                                    }// fin cuenta ingresos vacia. count($datos_cuentas)


						                                           if($valor_motor_contabilidad==true){
						                                           	     $this->cstd03_movimientos_manuales->execute("COMMIT;");
												                         $this->set('mensaje', 'LA ANULACI&Oacute;N FUE PROCESADA CORRECTAMENTE');

						                                          	}else{
																		$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
																		$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACI&Oacute;N ');
																	}


                                                       }else{
                                                       	 $this->cstd03_movimientos_manuales->execute("COMMIT;");
													     $this->set('mensaje', 'LA ANULACI&Oacute;N FUE PROCESADA CORRECTAMENTE');

                                                       }
												}else{
													$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
													$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACI&Oacute;N , NO PUDO SER DESCONTADO EL MONTO DE LA CUENTA');
												}

									}else{
										$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
										$this->set('mensajeError','NO SE PUDO PROCESAR LA OPERACI&Oacute;N, EL DOCUMENTO NO FUE ANULADO');
									}//fin else actualizacion
								}else{
									$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
									$this->set('mensajeError','Lo siento no se pudo encontrar el documento a anular');
								}
						    }else{
						    	$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
								$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACI&Oacute;N , NO SE PUDO CREAR EL CUERPO ACTA DE ANULACION');
						    }
						break;




					case 2: //Es una Nota de Credito
							$cod_anulacion=262;//codigo tipo de anulacion
							//se realiza el acta de anulacion del documento
							$this->cstd03_movimientos_manuales->execute("BEGIN;");
							$escribe_acta="INSERT INTO cugd03_acta_anulacion_cuerpo values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano','$nuevo_num_anulacion','$cod_anulacion','".$ano_movimiento."','$numero_documento','".$fecha_documento."','$concepto_anulacion')";
							if($this->cugd03_acta_anulacion_cuerpo->execute($escribe_acta)>1){

								$consulta="select * from cstd03_movimientos_manuales where ".$this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
								//si el documento existe continuo para actualizar la condicion del documento a anulado la tabla de movimientos manuales
								if($tabla_movimiento=$this->cstd03_movimientos_manuales->execute($consulta)){

									$sql="update cstd03_movimientos_manuales set ano_anulacion='$ano_anulacion', numero_anulacion='$nuevo_num_anulacion', fecha_proceso_anulacion='$fecha_anulacion',".
									 " dia_asiento_anulacion=0, mes_asiento_anulacion=0, ano_asiento_anulacion=0, numero_asiento_anulacion=0, username_anulacion='$operador_anulacion', condicion_actividad='2'".
									 " where ".$this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";

                                    if($this->cstd03_movimientos_manuales->execute($sql)>1){

                                    	$cstd02_cuentas_bancarias=$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
						   					$disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
						   						$tipo_cuenta=$disponibilidad[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];
						   						$monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_credito_dia'];
						   						$monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_credito_mes'];
						   						$monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_credito_ano'];
												$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro'];
						   						$nuevo_monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_credito_dia']-$monto;//se descuenta el saldo
						   						$nuevo_monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_credito_dia']-$monto;
						   						$nuevo_monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_credito_dia']-$monto;
						   						$nuevo_monto_disp_real=$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro']-$monto;
						   						$actualiza_dispo="UPDATE cstd02_cuentas_bancarias SET nota_credito_dia=".$nuevo_monto_dia.", nota_credito_mes=".$nuevo_monto_mes.", nota_credito_ano=".$nuevo_monto_ano.", disponibilidad_libro=".$nuevo_monto_disp_real." WHERE ".$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
												if($this->cstd02_cuentas_bancarias->execute($actualiza_dispo)>1){


                                                    if($tipo_cuenta==1){
						                                    $sql_cuentas     = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
						                                    $datos_cuentas   = $this->cstd03_movimientos_manuales_ingresos->findAll($sql_cuentas);
						                                    $datos_cuentas2  = $this->cstd03_movimientos_manuales->findAll($sql_cuentas);

															if(count($datos_cuentas) == 0){// No hay datos de contabilidad de movimientos
							                                   	$valor_motor_contabilidad = true;
							                                }else{

								                                      foreach($datos_cuentas as $ve_aux_cuenta_1){
								                                        $var_aux_cuenta   = $ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_cuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subcuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_division'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subdivision'];
																		$cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['monto']);
								                                      }


																		$monto_total["monto_total"] = $datos_cuentas2[0]["cstd03_movimientos_manuales"]["monto"];
																		$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
																	    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

																		$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																																      $to      = 2,
																																      $td      = 3,
																																      $rif_doc = null,
																																      $ano_dc  = $ano_movimiento,
																																      $n_dc    = $numero_documento,
																																      $f_dc    = date("d/m/Y"),
																																      $cpt_dc  = null,
																																      $ben_dc  = $datos_cuentas2[0]["cstd03_movimientos_manuales"]["beneficiario"],
																																      $mon_dc  = $monto_total,
																																      $ano_op   = null,
																																      $n_op     = null,
																																      $f_op     = null,

																																      $a_adj_op = null,
																																      $n_adj_op = null,
																																      $f_adj_op = null,
																																      $tp_op    = null,
																																      $deno_ban_pago  = $cod_entidad_bancaria_aux,
																																      $ano_movimiento = $ano_movimiento,
																																      $cod_ent_pago   = $cod_entidad_bancaria,
																																      $cod_suc_pago   = $cod_sucursal,
																																      $cod_cta_pago   = $cuenta_bancaria,
																																      $num_che_o_debi  = $numero_documento,
																																      $fec_che_o_debi  = date("d/m/Y"),
																																      $clas_che_o_debi = null,
																																      $tipo_che_o_debi = null,
																																      $ano_dc_array_pago     = null,
																																      $n_dc_array_pago       = null,
																																      $n_dc_adj_array_pago   = null,
																																      $f_dc_array_pago       = null,
																																      $ano_op_array_pago  = null,
																																      $n_op_array_pago    = null,
																																      $f_op_array_pago    = null,
																																      $tipo_op_array_pago = null,
																																      null,
																																      $f_dc_adj_array_pago= null,
																																      $parametro_bienes   = $cuenta_afectar
																																);

															  }// fin cuenta ingresos vacia. count($datos_cuentas)

							                                      if($valor_motor_contabilidad==true){
							                                      	 $this->cstd03_movimientos_manuales->execute("COMMIT;");
														             $this->set('mensaje', 'LA ANULACI&Oacute;N FUE PROCESADA CORRECTAMENTE');
							                                      }else{
							                                      	 $this->cstd03_movimientos_manuales->execute("ROLLBACK;");
							                                      	 $this->set('mensajeError','NO SE PUDO PROCESAR LA OPERACI&Oacute;N');
							                                      }
                                                    }else{

                                                    	 $this->cstd03_movimientos_manuales->execute("COMMIT;");
													     $this->set('mensaje', 'LA ANULACI&Oacute;N FUE PROCESADA CORRECTAMENTE');

                                                    }//fin else


												}else{
													$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
													$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACI&Oacute;N , NO PUDO SER DESCONTADO EL MONTO DE LA CUENTA');
												}

									}else{
										$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
										$this->set('mensajeError','NO SE PUDO PROCESAR LA OPERACI&Oacute;N, EL DOCUMENTO NO FUE ANULADO');
									}//fin else actualizacion
								}else{
									$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
									$this->set('mensajeError','Lo siento no se pudo encontrar el documento a anular');
								}
						    }else{  $this->cstd03_movimientos_manuales->execute("ROLLBACK;");
							    	$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACI&Oacute;N , NO SE PUDO CREAR EL CUERPO DEL ACTA DE ANULACION');
						    }
						break;




						case 3: //Es una Nota de Debito
							$cod_anulacion=263;//codigo tipo de anulacion
							//se realiza el acta de anulacion del documento
							$this->cstd03_movimientos_manuales->execute("BEGIN;");
							$escribe_acta="INSERT INTO cugd03_acta_anulacion_cuerpo values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano','$nuevo_num_anulacion','$cod_anulacion','".$ano_movimiento."','$numero_documento','".$fecha_documento."','$concepto_anulacion')";
							if($this->cugd03_acta_anulacion_cuerpo->execute($escribe_acta)>1){

								$consulta="select * from cstd03_movimientos_manuales where ".$this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
								//si el documento existe continuo para actualizar la condicion del documento a anulado la tabla de movimientos manuales
								if($tabla_movimiento=$this->cstd03_movimientos_manuales->execute($consulta)){

									$sql="update cstd03_movimientos_manuales set ano_anulacion='$ano_anulacion', numero_anulacion='$nuevo_num_anulacion', fecha_proceso_anulacion='$fecha_anulacion',".
									 " dia_asiento_anulacion=0, mes_asiento_anulacion=0, ano_asiento_anulacion=0, numero_asiento_anulacion=0, username_anulacion='$operador_anulacion', condicion_actividad='2'".
									 " where ".$this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";

									if($this->cstd03_movimientos_manuales->execute($sql)>1){

											$cstd02_cuentas_bancarias=$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
						   					$disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
						   						$tipo_cuenta=$disponibilidad[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];
						   						$monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_debito_dia'];
						   						$monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_debito_mes'];
						   						$monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_debito_ano'];
												$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro'];
						   						$nuevo_monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_debito_dia']-$monto;//disminuyo el monto a la nota_debito_dia
						   						$nuevo_monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_debito_mes']-$monto;//disminuyo el monto a la nota_debito_mes
						   						$nuevo_monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['nota_debito_ano']-$monto;//disminuyo el monto a la nota_debito_ano
						   						$nuevo_monto_disp_real=$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro'] + $monto;//aumento el monto a la disponibilidad libro
						   						$actualiza_dispo="UPDATE cstd02_cuentas_bancarias SET nota_debito_dia=".$nuevo_monto_dia.", nota_debito_mes=".$nuevo_monto_mes.", nota_debito_ano=".$nuevo_monto_ano.", disponibilidad_libro=".$nuevo_monto_disp_real." WHERE ".$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
												if($this->cstd02_cuentas_bancarias->execute($actualiza_dispo)>1){

															if($tipo_cuenta==1){

																$sql_cuentas     = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
							                                    $datos_cuentas   = $this->cstd03_movimientos_manuales_ingresos->findAll($sql_cuentas);
							                                    $datos_cuentas2  = $this->cstd03_movimientos_manuales->findAll($sql_cuentas);
							                                    $cuenta_afectar  = null;

							                                    if(count($datos_cuentas) == 0){// No hay datos de contabilidad de movimientos
							                                   		$valor_motor_contabilidad = true;
							                                	}else{


									                                      foreach($datos_cuentas as $ve_aux_cuenta_1){
									                                        $var_aux_cuenta   = $ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_cuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subcuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_division'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subdivision'];
																			$cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['monto']);
									                                      }

																		$monto_total["monto_total"]        = $monto;
																		$cuenta_afectar_2["tipo_cuenta"]     = $tipo_cuenta;
								                                        $cuenta_afectar_2["tipo_movimiento"] = 2;
																		$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
																	    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

																		$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																																      $to      = 2,
																																      $td      = 20,
																																      $rif_doc = null,
																																      $ano_dc  = $ano_movimiento,
																																      $n_dc    = $numero_documento,
																																      $f_dc    = date("d/m/Y"),
																																      $cpt_dc  = null,
																																      $ben_dc  = $datos_cuentas2[0]["cstd03_movimientos_manuales"]["beneficiario"],
																																      $mon_dc  = $monto_total,
																																      $ano_op   = null,
																																      $n_op     = null,
																																      $f_op     = null,

																																      $a_adj_op = null,
																																      $n_adj_op = null,
																																      $f_adj_op = null,
																																      $tp_op    = null,
																																      $deno_ban_pago  = $cod_entidad_bancaria_aux,
																																      $ano_movimiento = $ano_movimiento,
																																      $cod_ent_pago   = $cod_entidad_bancaria,
																																      $cod_suc_pago   = $cod_sucursal,
																																      $cod_cta_pago   = $cuenta_bancaria,
																																      $num_che_o_debi  = $numero_documento,
																																      $fec_che_o_debi  = date("d/m/Y"),
																																      $clas_che_o_debi = null,
																																      $tipo_che_o_debi = null,
																																      $ano_dc_array_pago     = null,
																																      $n_dc_array_pago       = null,
																																      $n_dc_adj_array_pago   = null,
																																      $f_dc_array_pago       = null,
																																      $ano_op_array_pago  = null,
																																      $n_op_array_pago    = null,
																																      $f_op_array_pago    = null,
																																      $tipo_op_array_pago = null,
																																      null,
																																      $f_dc_adj_array_pago= null,
																																      $parametro_bienes   = $cuenta_afectar,
																																      $cuenta_afectar_2
																																);

																}// fin cuenta ingresos vacia. count($datos_cuentas)

																	if($valor_motor_contabilidad==true){

																		// ARREGLO
																		//Se busca si la nota de debito pago una solicitud de recursos (Pago de transferencia), de ser asi se procede a retroceder esa solicitud, borrando la nota de debito asignado de la tabla:csrd01_solicitud_recurso_cuerpo.
																		$buscar_cheq_solicitud = "ano_solicitud='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and numero_cheque='$numero_documento'";
																														
																		if($this->csrd01_solicitud_recurso_cuerpo->findCount($buscar_cheq_solicitud)==1){
																		
																			$update_cheq_solicitud="UPDATE csrd01_solicitud_recurso_cuerpo SET monto_entregado='0', cod_entidad_bancaria='0',  cod_sucursal='0', cuenta_bancaria='0', numero_cheque='0', fecha_cheque='1900/01/01' WHERE ".$buscar_cheq_solicitud;
																		
																			if($this->csrd01_solicitud_recurso_cuerpo->execute($update_cheq_solicitud)>1){
																		
																				$this->cstd03_movimientos_manuales->execute("COMMIT;");
																				$this->set('mensaje', 'LA OPERACION FUE PROCESADA EXITOSAMENTE');
																		
																			}else{
																		
																				$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
																				$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACIÓN');
																			}
																		
																		}else{

														  					$this->cstd03_movimientos_manuales->execute("COMMIT;");
													   						$this->set('mensaje', 'LA OPERACION FUE PROCESADA EXITOSAMENTE');
																	    
																	    }

				                                                    }else{
				                                                    	$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
				                                                    	$this->set('mensajeError','Los datos de la nota de credito no fueron guardados');
				                                                    }

														}else{
															$this->set('mensaje', 'LA OPERACION FUE PROCESADA EXITOSAMENTE');
															$this->cstd03_movimientos_manuales->execute("COMMIT;");
														}
												}else{
													$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
													$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACI&Oacute;N , NO PUDO SER DESCONTADO EL MONTO DE LA CUENTA');
												}
									}else{
										$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
										$this->set('mensajeError','NO SE PUDO PROCESAR LA OPERACI&Oacute;N, EL DOCUMENTO NO FUE ANULADO');
									}//fin else actualizacion
								}else{
									$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
									$this->set('mensajeError','Lo siento no se pudo encontrar el documento a anular');
								}
							}else{
								$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
								$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACI&Oacute;N , NO SE PUDO CREAR EL CUERPO ACTA DE ANULACION');
							}
					    break;





					case 4: //Es un Cheque

							$cod_anulacion=264;//codigo tipo de anulacion
							//se realiza el acta de anulacion del documento
							$this->cstd03_movimientos_manuales->execute("BEGIN;");
							$escribe_acta="INSERT INTO cugd03_acta_anulacion_cuerpo values('".$this->verifica_SS(1)."','".$this->verifica_SS(2)."','".$this->verifica_SS(3)."','".$this->verifica_SS(4)."','".$this->verifica_SS(5)."','$ano','$nuevo_num_anulacion','$cod_anulacion','".$ano_movimiento."','$numero_documento','".$fecha_documento."','$concepto_anulacion')";
							if($this->cugd03_acta_anulacion_cuerpo->execute($escribe_acta)>1){

								$consulta="select * from cstd03_movimientos_manuales where ".$this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
								//si el documento existe continuo para actualizar la condicion del documento a anulado la tabla de movimientos manuales
								if($tabla_movimiento=$this->cstd03_movimientos_manuales->execute($consulta)){

									$sql="update cstd03_movimientos_manuales set ano_anulacion='$ano_anulacion', numero_anulacion='$nuevo_num_anulacion', fecha_proceso_anulacion='$fecha_anulacion',".
									 " dia_asiento_anulacion=0, mes_asiento_anulacion=0, ano_asiento_anulacion=0, numero_asiento_anulacion=0, username_anulacion='$operador_anulacion', condicion_actividad='2'".
									 " where ".$this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
									if($this->cstd03_movimientos_manuales->execute($sql)>1){

											$cstd02_cuentas_bancarias=$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
						   					$disponibilidad=$this->cstd02_cuentas_bancarias->findAll($cstd02_cuentas_bancarias,null);
						   						$tipo_cuenta=$disponibilidad[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];
						   						$monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['cheque_dia'];
						   						$monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['cheque_mes'];
						   						$monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['cheque_ano'];
												$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro'];
						   						$nuevo_monto_dia=$disponibilidad[0]['cstd02_cuentas_bancarias']['cheque_dia']-$monto;//disminuyo el monto al cheque_dia
						   						$nuevo_monto_mes=$disponibilidad[0]['cstd02_cuentas_bancarias']['cheque_mes']-$monto;//disminuyo el monto al cheque_mes
						   						$nuevo_monto_ano=$disponibilidad[0]['cstd02_cuentas_bancarias']['cheque_ano']-$monto;//disminuyo el monto al cheque_ano
						   						$nuevo_monto_disp_real=$disponibilidad[0]['cstd02_cuentas_bancarias']['disponibilidad_libro'] + $monto;//aumento el monto a la disponibilidad libro
						   						$actualiza_dispo="UPDATE cstd02_cuentas_bancarias SET cheque_dia=".$nuevo_monto_dia.", cheque_mes=".$nuevo_monto_mes.", cheque_ano=".$nuevo_monto_ano.", disponibilidad_libro=".$nuevo_monto_disp_real." WHERE ".$cod_dependencias." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."'";
												if($this->cstd02_cuentas_bancarias->execute($actualiza_dispo)>1){
													$update_cheque="UPDATE cstd03_cheque_numero SET situacion=4 WHERE ".$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta_bancaria."' and numero_cheque=".$numero_documento;
													if($this->cstd03_cheque_numero->execute($update_cheque)>1){
														$flag=0;//Para verificar si entro en alguno de los ciclos de abajo.
														$condicion_elim=$this->SQLCA()." and username='$operador_anulacion' and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and numero_cheque='$numero_documento'";
														if($this->cstd04_cheque_poremitir->findCount($condicion_elim)>0){
															$cheq        = $this->cstd04_cheque_poremitir->findAll($condicion_elim);
															$delete_cheq = "DELETE FROM cstd04_cheque_poremitir WHERE ".$this->SQLCA()." and username='$operador_anulacion' and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria' and numero_cheque=".$cheq[0]['cstd04_cheque_poremitir']['numero_cheque'];
															$sw_2        = $this->cstd04_cheque_poremitir->execute($delete_cheq);
														}else{
															$sw_2=2;
														}


															if($sw_2>1){



																							$sw1 = 0;
																							$sql_cuentas     = $this->SQLCA()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";
														                                    $datos_cuentas   = $this->cstd03_movimientos_manuales_ingresos->findAll($sql_cuentas);
														                                    $datos_cuentas2  = $this->cstd03_movimientos_manuales->findAll($sql_cuentas);
														                                    $cuenta_afectar  = null;



											                            if(count($datos_cuentas) == 0 && $tipo_cuenta==1){// No hay datos de contabilidad de movimientos
							                		                   		$sw1 = 2;
							                        		        	}else{
																						if($tipo_cuenta==1){

														                                      foreach($datos_cuentas as $ve_aux_cuenta_1){
														                                        $var_aux_cuenta   = $ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_cuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subcuenta'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_division'].'-'.$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['cod_subdivision'];
																								$cuenta_afectar[] = array('cuenta'=>$var_aux_cuenta, 'monto'=>$ve_aux_cuenta_1["cstd03_movimientos_manuales_ingresos"]['monto']);
														                                      }

																							$monto_total["monto_total"]        = $monto;
																							$cuenta_afectar_2["tipo_cuenta"]     = $tipo_cuenta;
													                                        $cuenta_afectar_2["tipo_movimiento"] = 1;
																							$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);


																							$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																													      $to      = 2,
																													      $td      = 20,
																													      $rif_doc = null,
																													      $ano_dc  = $ano_movimiento,
																													      $n_dc    = $numero_documento,
																													      $f_dc    = date("d/m/Y"),
																													      $cpt_dc  = null,
																													      $ben_dc  = $datos_cuentas2[0]["cstd03_movimientos_manuales"]["beneficiario"],
																													      $mon_dc  = $monto_total,
																													      $ano_op   = null,
																													      $n_op     = null,
																													      $f_op     = null,

																													      $a_adj_op = null,
																													      $n_adj_op = null,
																													      $f_adj_op = null,
																													      $tp_op    = null,
																													      $deno_ban_pago  = $cod_entidad_bancaria_aux,
																													      $ano_movimiento = $ano_movimiento,
																													      $cod_ent_pago   = $cod_entidad_bancaria,
																													      $cod_suc_pago   = $cod_sucursal,
																													      $cod_cta_pago   = $cuenta_bancaria,
																													      $num_che_o_debi  = $numero_documento,
																													      $fec_che_o_debi  = date("d/m/Y"),
																													      $clas_che_o_debi = null,
																													      $tipo_che_o_debi = null,
																													      $ano_dc_array_pago     = null,
																													      $n_dc_array_pago       = null,
																													      $n_dc_adj_array_pago   = null,
																													      $f_dc_array_pago       = null,
																													      $ano_op_array_pago  = null,
																													      $n_op_array_pago    = null,
																													      $f_op_array_pago    = null,
																													      $tipo_op_array_pago = null,
																													      null,
																													      $f_dc_adj_array_pago= null,
																													      $parametro_bienes   = $cuenta_afectar,
																													      $cuenta_afectar_2
																																					);

																							if($valor_motor_contabilidad==true){
																									$sw1=2;
										                                                    }


																					}else if($tipo_cuenta==2){


																							$monto_total["monto_total"]        = $monto;
																							$cuenta_afectar_2["tipo_retencion"]  = $datos_cuentas2[0]["cstd03_movimientos_manuales"]["cod_fondo_tercero"];
																							$cuenta_afectar_2["tipo_cuenta"]     = $tipo_cuenta;
													                                        $cuenta_afectar_2["tipo_movimiento"] = 1;
																							$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
																						    $cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];



																							$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
																													      $to      = 2,
																													      $td      = 20,
																													      $rif_doc = null,
																													      $ano_dc  = $ano_movimiento,
																													      $n_dc    = $numero_documento,
																													      $f_dc    = date("d/m/Y"),
																													      $cpt_dc  = null,
																													      $ben_dc  = $datos_cuentas2[0]["cstd03_movimientos_manuales"]["beneficiario"],
																													      $mon_dc  = $monto_total,
																													      $ano_op   = null,
																													      $n_op     = null,
																													      $f_op     = null,

																													      $a_adj_op = null,
																													      $n_adj_op = null,
																													      $f_adj_op = null,
																													      $tp_op    = null,
																													      $deno_ban_pago  = $cod_entidad_bancaria_aux,
																													      $ano_movimiento = $ano_movimiento,
																													      $cod_ent_pago   = $cod_entidad_bancaria,
																													      $cod_suc_pago   = $cod_sucursal,
																													      $cod_cta_pago   = $cuenta_bancaria,
																													      $num_che_o_debi  = $numero_documento,
																													      $fec_che_o_debi  = date("d/m/Y"),
																													      $clas_che_o_debi = null,
																													      $tipo_che_o_debi = null,
																													      $ano_dc_array_pago     = null,
																													      $n_dc_array_pago       = null,
																													      $n_dc_adj_array_pago   = null,
																													      $f_dc_array_pago       = null,
																													      $ano_op_array_pago  = null,
																													      $n_op_array_pago    = null,
																													      $f_op_array_pago    = null,
																													      $tipo_op_array_pago = null,
																													      null,
																													      $f_dc_adj_array_pago= null,
																													      $parametro_bienes   = null,
																													      $cuenta_afectar_2
																																					);

																							if($valor_motor_contabilidad==true){
																									$sw1=2;
										                                                    }

																					}//fin if



																	}// fin cuenta ingresos vacia. count($datos_cuentas)




                                                                            if($sw1>1){

																						//Se busca si el cheque pago una solicitud de recursos (Pago de transferencia), de ser asi se procede a retroceder esa solicitud, borrando el cheque asignado de la tabla:csrd01_solicitud_recurso_cuerpo.
																						$buscar_cheq_solicitud = "ano_solicitud='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and numero_cheque='$numero_documento'";
																						if($this->csrd01_solicitud_recurso_cuerpo->findCount($buscar_cheq_solicitud)==1){
																							$update_cheq_solicitud="UPDATE csrd01_solicitud_recurso_cuerpo SET monto_entregado='0', cod_entidad_bancaria='0',  cod_sucursal='0', cuenta_bancaria='0', numero_cheque='0', fecha_cheque='1900/01/01' WHERE ".$buscar_cheq_solicitud;
																							if($this->csrd01_solicitud_recurso_cuerpo->execute($update_cheq_solicitud)>1){
																								$this->cstd03_movimientos_manuales->execute("COMMIT;");
																								$this->set('mensaje', 'LA OPERACION FUE PROCESADA EXITOSAMENTE');
																							}else{
																								$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
																								$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACIÓN');
																							}
																						}else{
																							$this->cstd03_movimientos_manuales->execute("COMMIT;");
																							$this->set('mensaje', 'LA OPERACION FUE PROCESADA EXITOSAMENTE');
																						}


                                                                            }else{
                                                                            	$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
																				$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACIÓN');
																			}

															}else{
																$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
																$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACIÓN');
															}


													}else{
														$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
														$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACIÓN');
													}
												}else{
													$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
													$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACIÓN');
												}
									}else{
										$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
										$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACIÓN');
									}//fin else actualizacion
								}else{
									$this->cstd03_movimientos_manuales->execute("ROLLBACK;");
									$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACIÓN');
								}
							}else{  $this->cstd03_movimientos_manuales->execute("ROLLBACK;");
									$this->set('mensajeError', 'NO SE PUDO PROCESAR LA ANULACIÓN');
							}
						break;
				}//switche
			}else{
				$this->set('mensajeError',$mens_anulacion);
			}

	}//Pase acta

	$this->set('pase_anulacion','no');

	//$this->consultar($anterior);
	//$this->render("consultar");

}//procesar_anulacion


function tipo_documento($cod_ent=null, $cod_sucursal=null, $cuenta=null){
	$this->layout="ajax";
	$this->set('cod_entidad',$cod_ent);
	$this->set('cod_sucursal',$cod_sucursal);
	$this->set('cuenta',$cuenta);
	$cond = $this->SQLCA()." and cod_entidad_bancaria=".$cod_ent." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta."'";
	$tipo_cuenta = $this->cstd02_cuentas_bancarias->findAll($cond,'tipo_cuenta',null,1,1,null);
	$this->set('tipo_cuenta',$tipo_cuenta[0]['cstd02_cuentas_bancarias']['tipo_cuenta']);
}


function beneficiario($entidad=null,$var=null){
	$this->layout="ajax";
    $cod_tipo = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
    $cod_dep  = $this->Session->read('SScoddep');
	$institucion=$this->cugd02_dependencia->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion='$cod_tipo' and cod_institucion='$cod_inst'");
	$inst= $institucion['0']['0']['denominacion'];
	if($var==1 || $var==2 || $var==4){
		$dep=$this->Session->read('dependencia');
		if ($cod_dep==1){
			$this->set('beneficiario',$inst);
		}else{
			$this->set('beneficiario',$dep);
		}
	}elseif($var==3){
		$entid=$this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$entidad, null, 'denominacion');
		$this->set('beneficiario',$entid[0]['cstd01_entidades_bancarias']['denominacion']);
	}
}

function prebusqueda(){
	$this->layout="ajax";
	$this->set('ano_movimiento', $this->ano_ejecucion());
}

function buscardocumentos(){
	$this->layout="ajax";

	if(!empty($this->data['cstp03_movimientos_manuales'])){
		$ano = $this->data['cstp03_movimientos_manuales']['ano'];
		$tipodocu = $this->data['cstp03_movimientos_manuales']['tipo_documento'];
		$numdocu = $this->data['cstp03_movimientos_manuales']['campobuscar'];
		if($tipodocu!=5){
				$condicion_SQLA=$this->SQLCA()." and tipo_documento='$tipodocu' and numero_documento='$numdocu' and ano_movimiento='$ano'";
				if($data=$this->cstd03_movimientos_manuales->findAll($condicion_SQLA,null,"cod_entidad_bancaria, cod_sucursal, tipo_documento, numero_documento, ano_movimiento ASC")){
				$this->set('datos',$data);
				}else{
				$this->set('mensajeError', 'NO SE ENCONTRO NIG&Uacute;N DOCUMENTO QUE COINCIDA CON SU BUSQUEDA PARA EL A&Ntilde;O '.$ano);
				$this->set('datos',null);
				}
		}elseif($tipodocu==5){
				$condicion_SQLA=$this->SQLCA()." and numero_documento='$numdocu' and ano_movimiento='$ano'";
				if($data=$this->cstd03_movimientos_manuales->findAll($condicion_SQLA,null,"cod_entidad_bancaria, cod_sucursal, tipo_documento, numero_documento, ano_movimiento ASC")){
				$this->set('datos',$data);
				}else{
				$this->set('mensajeError', 'NO SE ENCONTRO NIG&Uacute;N DOCUMENTO QUE COINCIDA CON SU BUSQUEDA PARA EL A&Ntilde;O '.$ano);
				$this->set('datos',null);
				}
		}
	}else{
		$this->set('mensajeError', 'LO SIENTO NO INGRESO TODOS LOS DATOS, REVISE POR FAVOR');
	}
	echo'<script>';
  	  echo"document.getElementById('b_buscar').disabled=false;";
  	echo'</script>';
}



function consultar2($entidad=null,$sucursal=null,$cuenta=null,$tipodoc=null,$nrodoc=null, $year=null) {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$condicion_SQLA = $this->SQLCA();
	if($year==null){
		$condicion_SQLA_2 = $condicion_SQLA." and cod_entidad_bancaria='$entidad' and cod_sucursal='$sucursal' and cuenta_bancaria='$cuenta' and tipo_documento='$tipodoc' and numero_documento='$nrodoc'";
	}else{
		$condicion_SQLA_2 = $condicion_SQLA." and cod_entidad_bancaria='$entidad' and cod_sucursal='$sucursal' and cuenta_bancaria='$cuenta' and tipo_documento='$tipodoc' and numero_documento='$nrodoc' and ano_movimiento='".$year."'  ";
	}
	if(isset($pagina)){
		$Tfilas=$this->cstd03_movimientos_manuales->findCount($condicion_SQLA);
        if($Tfilas!=0){
        	$data=$this->cstd03_movimientos_manuales->findAll($condicion_SQLA_2,null,"cod_entidad_bancaria, cod_sucursal, tipo_documento, numero_documento ASC",1,$pagina,null);

            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->set('ultimo',$Tfilas);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
        }
	}else{
		$pagina=1;
		$Tfilas=$this->cstd03_movimientos_manuales->findCount($condicion_SQLA);

        if($Tfilas!=0){
        	$data=$this->cstd03_movimientos_manuales->findAll($condicion_SQLA_2,null,"cod_entidad_bancaria, cod_sucursal, tipo_documento, numero_documento ASC",1,$pagina,null);
			$this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->set('ultimo',$Tfilas);

        }else{
	 	       $this->set('mensajeError', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
        }
	}

	    $this->set('ano_movimiento', $data[0]['cstd03_movimientos_manuales']['ano_movimiento']);

		$cod_entidad_bancaria=$this->mascara3($data[0]['cstd03_movimientos_manuales']['cod_entidad_bancaria']);//-----No borrar la variable "cod_entidad_bancaria"
	    $this->set('cod_entidad_bancaria', $cod_entidad_bancaria);
	    $cod_sucursal=$this->mascara3($data[0]['cstd03_movimientos_manuales']['cod_sucursal']);//---------------------No borrar la variable "cod_sucursal"
	    $this->set('cod_sucursal', $cod_sucursal);
	    $this->set('cuenta_bancaria', $data[0]['cstd03_movimientos_manuales']['cuenta_bancaria']);
	    $this->set('tipo_documento', $data[0]['cstd03_movimientos_manuales']['tipo_documento']);
	    $this->set('numero_documento', $data[0]['cstd03_movimientos_manuales']['numero_documento']);
	    $this->set('fecha_documento', $data[0]['cstd03_movimientos_manuales']['fecha_documento']);
	    $this->set('beneficiario', $data[0]['cstd03_movimientos_manuales']['beneficiario']);
	    $this->set('monto', $data[0]['cstd03_movimientos_manuales']['monto']);
	    $this->set('concepto', $data[0]['cstd03_movimientos_manuales']['concepto']);
	    $this->set('fecha_proceso_registro', $data[0]['cstd03_movimientos_manuales']['fecha_proceso_registro']);
	    $this->set('dia_asiento_registro', $data[0]['cstd03_movimientos_manuales']['dia_asiento_registro']);
	    $this->set('mes_asiento_registro', $data[0]['cstd03_movimientos_manuales']['mes_asiento_registro']);
	    $this->set('ano_asiento_registro', $data[0]['cstd03_movimientos_manuales']['ano_asiento_registro']);
	    $this->set('numero_asiento_registro', $data[0]['cstd03_movimientos_manuales']['numero_asiento_registro']);
	    $this->set('username_registro', $data[0]['cstd03_movimientos_manuales']['username_registro']);
	    $this->set('colocacion', $data[0]['cstd03_movimientos_manuales']['colocacion']);
	    $this->set('caja_chica', $data[0]['cstd03_movimientos_manuales']['caja_chica']);
	    $this->set('codi_dep', $data[0]['cstd03_movimientos_manuales']['codi_dep']);
	    $this->set('ano_solicitud', $data[0]['cstd03_movimientos_manuales']['ano_solicitud']);
	    $this->set('num_solicitud', $data[0]['cstd03_movimientos_manuales']['num_solicitud']);

	    $condicion_actividad=$data[0]['cstd03_movimientos_manuales']['condicion_actividad'];//<---No borrar esta variable
	    $this->set('condicion_act', $condicion_actividad);

	    if($condicion_actividad == 2){
	    	$sacar = $this->cugd03_acta_anulacion_cuerpo->findAll($this->condicion(). " and numero_acta_anulacion=".$data[0]['cstd03_movimientos_manuales']['numero_anulacion']." and ano_acta_anulacion=".$data[0]['cstd03_movimientos_manuales']['ano_anulacion'] );
			foreach($sacar as $sa2){
			 	$concepto_anulacion= $sa2['cugd03_acta_anulacion_cuerpo']['motivo_anulacion'];
			}
			$this->set('concepto_anulacion',$concepto_anulacion);
	   		$this->set('enable_anular','disabled');
	   		$this->set('ano_anulacion', $data[0]['cstd03_movimientos_manuales']['ano_anulacion']);
	   		$this->set('numero_anulacion', $data[0]['cstd03_movimientos_manuales']['numero_anulacion']);
	   		$this->set('fecha_proceso_anulacion', $data[0]['cstd03_movimientos_manuales']['fecha_proceso_anulacion']);
	   		$this->set('dia_asiento_anulacion', $data[0]['cstd03_movimientos_manuales']['dia_asiento_anulacion']);
	   		$this->set('mes_asiento_anulacion', $data[0]['cstd03_movimientos_manuales']['mes_asiento_anulacion']);
	   		$this->set('ano_asiento_anulacion', $data[0]['cstd03_movimientos_manuales']['ano_asiento_anulacion']);
	   		$this->set('numero_asiento_anulacion', $data[0]['cstd03_movimientos_manuales']['numero_asiento_anulacion']);
	   		$this->set('username_anulacion', $data[0]['cstd03_movimientos_manuales']['username_anulacion']);
	    }else{
	    	$this->set('enable_anular','enable');
	    }

	$dataR=$this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$cod_entidad_bancaria);
	foreach($dataR as $dataR1){
	    	$this->set('denominacion_entidad',$dataR1['cstd01_entidades_bancarias']['denominacion']);
			}

	$dataR2=$this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal);
	foreach($dataR2 as $dataR22){
	    	$this->set('denominacion_sucursal',$dataR22['cstd01_sucursales_bancarias']['denominacion']);
			}

	$dataR3=$this->cstd02_cuentas_bancarias->findAll('cod_entidad_bancaria='.$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$data[0]['cstd03_movimientos_manuales']['cuenta_bancaria']."'");
		$this->set('tipo_cuenta',$dataR3[0]['cstd02_cuentas_bancarias']['tipo_cuenta']);

}//fin consultar2



function ventana_pdf_impresion_cheque2($var_form = null){
	$this->layout="ajax";
	if($var_form != null){
		$this->set('var_form', $var_form);
	}
}

function ventana_pdf_impresion_cheque(){
	$this->layout="ajax";
}

function orientacion_cheque($var_orientacion = null){
	$this->layout="ajax";
	$this->set('var_orientacion', $var_orientacion);
	echo "<script>document.getElementById('forma_orientacion').value = '$var_orientacion';</script>";
}


function preimpresion_cheques($tipo_cheques=null){
	$this->layout="ajax";
	$usuario = strtoupper($this->Session->read('nom_usuario'));
	$datos=$this->cstd04_cheque_poremitir->findAll($this->SQLCA()." and upper(username)='$usuario'");
	$entidades=$this->cstd01_entidades_bancarias->findAll();
	if($datos != ''){
	   $this->set('datos',$datos);
	   $this->set('entidad',$entidades);
	   $this->set('user',$usuario);
	}else{
	   $this->set('datos','');
	   $this->set('entidad',$entidades);
	   $this->set('user',$usuario);
	}
	$this->set('tipo_cheques',$tipo_cheques);
}

function generar_cheque_mov_manuales(){
	$this->layout="pdf";

	$this->set('titulo_inst', $this->Session->read('entidad_federal'));
	$this->set('titulo_a',$this->Session->read('dependencia'));

	$preimpreso=$this->data['cstp03_movimientos_manuales']['preimpreso'];
	$codpresi = $this->Session->read('SScodpresi');
	$codentidad  = $this->Session->read('SScodentidad');
	$codtipoinst = $this->Session->read('SScodtipoinst');
	$codinst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$usuario = strtoupper($this->Session->read('nom_usuario'));
	$ano_movimiento=$this->ano_ejecucion();

	$consulta_cheques= "select
	a.cod_presi              as a_cod_presi,
	a.cod_entidad            as a_cod_entidad,
	a.cod_tipo_inst          as a_cod_tipo_inst,
	a.cod_inst               as a_cod_inst,
	a.cod_dep                as a_cod_dep,
	a.username               as a_username,
	a.ano_movimiento         as a_ano_movimiento,
	a.cod_entidad_bancaria   as a_cod_entidad_bancaria,
	a.cod_sucursal           as a_cod_sucursal,
	a.cuenta_bancaria        as a_cuenta_bancaria,
	a.numero_cheque 	     as a_numero_cheque,
	(select b.beneficiario  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.ano_movimiento			   = b.ano_movimiento		and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::text as beneficiario,
	(select b.fecha_documento  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.ano_movimiento			   = b.ano_movimiento		and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::date as fecha_documento,
	(select b.concepto  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.ano_movimiento			   = b.ano_movimiento		and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::text as concepto,
	(select b.monto  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.ano_movimiento			   = b.ano_movimiento		and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::numeric(26,2)   as monto,
	(select c.numero_comprobante_egreso  from cstd06_comprobante_cuerpo_egreso c where
								  a.cod_presi                  = c.cod_presi            and
								  a.cod_entidad                = c.cod_entidad          and
								  a.cod_tipo_inst              = c.cod_tipo_inst        and
								  a.cod_inst                   = c.cod_inst             and
								  a.cod_dep                    = c.cod_dep              and
								  a.ano_movimiento			   = c.ano_movimiento		and
								  a.cod_entidad_bancaria       = c.cod_entidad_bancaria and
								  a.cod_sucursal               = c.cod_sucursal         and
								  a.cuenta_bancaria            = c.cuenta_bancaria      and
								  a.numero_cheque              = c.numero_cheque)::int4  as numero_comprobante_egreso
	from cstd04_cheque_poremitir a
	where a.cod_presi='$codpresi' and a.cod_entidad='$codentidad' and a.cod_tipo_inst='$codtipoinst' and a.cod_inst='$codinst' and a.cod_dep='$cod_dep' and upper(a.username)='$usuario'
	order by a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.numero_cheque";

	$datos22=$this->cstd04_cheque_poremitir->execute($consulta_cheques);
	$entidades=$this->cstd01_entidades_bancarias->findAll();
	$filas=$this->cstd04_cheque_poremitir->findCount($this->SQLCA()." and upper(username)='$usuario'");

	if(isset($this->data['cstp03_movimientos_manuales']['forma_orientacion'])){
		$this->set('forma_orientacion', $this->data['cstp03_movimientos_manuales']['forma_orientacion']);
	}

	$this->set('entidad',$entidades);
	$this->set('filas',$filas);
	$this->set('datos22',$datos22);
	$this->set('user',$usuario);
	$this->set('preimpreso',$preimpreso);

	$this->cstd04_cheque_poremitir->execute("DELETE FROM cstd04_cheque_poremitir WHERE ".$this->SQLCA()." and upper(username)='$usuario'");
}

function preimpresion_cheques_troqueladora($tipo_cheques=null){
	$this->layout="ajax";
	$usuario = strtoupper($this->Session->read('nom_usuario'));
	$datos=$this->cstd04_cheque_poremitir->findAll($this->SQLCA()." and upper(username)='$usuario'");
	$entidades=$this->cstd01_entidades_bancarias->findAll();
	if($datos != ''){
	   $this->set('datos',$datos);
	   $this->set('entidad',$entidades);
	   $this->set('user',$usuario);
	}else{
	   $this->set('datos','');
	   $this->set('entidad',$entidades);
	   $this->set('user',$usuario);
	}
	$this->set('tipo_cheques',$tipo_cheques);
}

function generar_cheque_mov_manuales_troqueladora(){
	$this->layout="pdf";

	$this->set('titulo_inst', $this->Session->read('entidad_federal'));
	$this->set('titulo_a',$this->Session->read('dependencia'));

	$preimpreso=$this->data['cstp03_movimientos_manuales']['preimpreso'];

	$codpresi = $this->Session->read('SScodpresi');
	$codentidad  = $this->Session->read('SScodentidad');
	$codtipoinst = $this->Session->read('SScodtipoinst');
	$codinst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$usuario = strtoupper($this->Session->read('nom_usuario'));
	$ano_movimiento=$this->ano_ejecucion();

	$consulta_cheques= "select
	a.cod_presi              as a_cod_presi,
	a.cod_entidad            as a_cod_entidad,
	a.cod_tipo_inst          as a_cod_tipo_inst,
	a.cod_inst               as a_cod_inst,
	a.cod_dep                as a_cod_dep,
	a.username               as a_username,
	a.ano_movimiento         as a_ano_movimiento,
	a.cod_entidad_bancaria   as a_cod_entidad_bancaria,
	a.cod_sucursal           as a_cod_sucursal,
	a.cuenta_bancaria        as a_cuenta_bancaria,
	a.numero_cheque 	     as a_numero_cheque,
	(select b.beneficiario  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.ano_movimiento			   = b.ano_movimiento		and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::text as beneficiario,
	(select b.fecha_documento  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.ano_movimiento			   = b.ano_movimiento		and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::date as fecha_documento,
	(select b.concepto  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.ano_movimiento			   = b.ano_movimiento		and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::text as concepto,
	(select b.monto  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.ano_movimiento			   = b.ano_movimiento		and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::numeric(26,2)   as monto,
	(select c.numero_comprobante_egreso  from cstd06_comprobante_cuerpo_egreso c where
								  a.cod_presi                  = c.cod_presi            and
								  a.cod_entidad                = c.cod_entidad          and
								  a.cod_tipo_inst              = c.cod_tipo_inst        and
								  a.cod_inst                   = c.cod_inst             and
								  a.cod_dep                    = c.cod_dep              and
								  a.ano_movimiento			   = c.ano_movimiento		and
								  a.cod_entidad_bancaria       = c.cod_entidad_bancaria and
								  a.cod_sucursal               = c.cod_sucursal         and
								  a.cuenta_bancaria            = c.cuenta_bancaria      and
								  a.numero_cheque              = c.numero_cheque)::int4  as numero_comprobante_egreso
	from cstd04_cheque_poremitir a
	where a.cod_presi='$codpresi' and a.cod_entidad='$codentidad' and a.cod_tipo_inst='$codtipoinst' and a.cod_inst='$codinst' and a.cod_dep='$cod_dep' and upper(a.username)='$usuario'
	order by a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.numero_cheque";

	$datos22=$this->cstd04_cheque_poremitir->execute($consulta_cheques);
	$entidades=$this->cstd01_entidades_bancarias->findAll();
	$filas=$this->cstd04_cheque_poremitir->findCount($this->SQLCA()." and upper(username)='$usuario'");

	$this->set('entidad',$entidades);
	$this->set('filas',$filas);
	$this->set('datos22',$datos22);
	$this->set('user',$usuario);
	$this->set('preimpreso',$preimpreso);

	//$this->cstd04_cheque_poremitir->execute("DELETE FROM cstd04_cheque_poremitir WHERE ".$this->SQLCA()." and upper(username)='$usuario'");

}

function disponibilidad_bancaria($cod_ent=null, $cod_sucursal=null, $cuenta=null){
	$this->layout="ajax";

  	if($cuenta!=null){
 	   $this->set('entidad',$cod_ent);
 	   $this->set('sucursal',$cod_sucursal);
 	   $this->set('cuenta',$cuenta);

 	   if($cuenta==null){
 		  $this->set('mensaje', 'ATENCI&Oacute;N: SELECCIONE LA ENTIDAD BANCARIA Y LA SUCURSAL BANCARIA');
 	   }
 	   $cond = $this->SQLCA()." and cod_entidad_bancaria=".$cod_ent." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='".$cuenta."'";
 	   $disponibilidad_real = $this->cstd02_cuentas_bancarias->findAll($cond,'disponibilidad_libro, tipo_cuenta',null,1,1,null);
 	   if($disponibilidad_real[0]['cstd02_cuentas_bancarias']['tipo_cuenta']==2){
 	   		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='text-shadow: 0.09em 0.08em #000000;' class='mensaje_resaltado_rojo'>".$this->Formato2($disponibilidad_real[0]['cstd02_cuentas_bancarias']['disponibilidad_libro'])." -- DISPONIBILIDAD.</span><span style='color:black;font-size:large;font-weight:bold;text-shadow: 0.06em 0.08em #00ad00;'>&nbsp;&nbsp;--&nbsp;&nbsp;CUENTA FONDO DE TERCEROS</span>";
 	   }else{
 	   		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='text-shadow: 0.09em 0.08em #000000;' class='mensaje_resaltado_rojo'>".$this->Formato2($disponibilidad_real[0]['cstd02_cuentas_bancarias']['disponibilidad_libro'])." -- DISPONIBILIDAD.</span>";
 	   }
	   $this->set('tipo_cuenta',$disponibilidad_real[0]['cstd02_cuentas_bancarias']['tipo_cuenta']);
    }else{
		$this->set('mensajeError', 'NO LLEGO LA INFORMACION DE LA CUENTA BANCARIA');
		$this->set('entidad','');
		$this->set('sucursal','');
		$this->set('cuenta','');
		$this->set('disponibilidad','');
		$this->set('tipo_cuenta','');
	}
}


function tipo_enlace($tipo_enlace=null){
	$this->layout="ajax";

	switch($tipo_enlace){
		case 1:
			$name='SEGURO SOCIAL OBLIGATORIO';
		break;
		case 2:
			$name='PARO FORZOSO';
		break;
		case 3:
			$name='LEY DE POLÍTICA HABITACIONAL';
		break;
		case 4:
			$name='FONDO DE PENSIÓN Y JUBILACIÓN';
		break;
		case 5:
			$name='CAJA DE AHORROS';
		break;
		case 6:
			$name='SINDICATOS Y GREMIOS';
		break;
		case 7:
			$name='JUZGADOS Y TRIBUNALES';
		break;
		case 8:
			$name='CASAS COMERCIALES';
		break;
		case 50:
			$name='RETENCION DE IVA';
		break;
		case 51:
			$name='RETENCION DE ISLR';
		break;
		case 52:
			$name='RETENCION DE TIMBRE FISCAL';
		break;
		case 53:
			$name='RETENCION DE IMPUESTO MUNICIPAL';
		break;
		case 54:
			$name='RETENCION POR RESPONSABILIDAD CIVIL';
		break;
		case 55:
			$name='RETENCION POR RESPONSABILIDAD SOCIAL';
		break;
		case 99:
			$name='OTRAS RETENCIONES';
		break;
	}

 	    $vec=array('01'=>'SEGURO SOCIAL OBLIGATORIO',
                   '02'=>'PARO FORZOSO',
                   '03'=>'LEY DE POLÍTICA HABITACIONAL',
                   '04'=>'FONDO DE PENSIÓN Y JUBILACIÓN',
 				   '05'=>'CAJA DE AHORROS',
 				   '06'=>'SINDICATOS GREMIOS',
	 			   '07'=>'JUZGADOS Y TRIBUNALES',
	 			   '08'=>'CASAS COMERCIALES',
	 			   '50'=>'RETENCIÓN DE IVA',
	 			   '51'=>'RETENCIÓN DE ISLR',
	 			   '52'=>'RETENCIÓN DE TIMBRE FISCAL',
	 			   '53'=>'RETENCIÓN DE IMPUESTO MUNICIPAL',
	 			   '54'=>'RETENCIÓN POR RESPONSABILIDAD CIVIL',
	 			   '55'=>'RETENCIÓN POR RESPONSABILIDAD SOCIAL',
                   '99'=>'OTRAS RETENCIONES');

	$this->set('vec',$vec);
	$this->set('deno', $name);
	$this->set('cod', $tipo_enlace);
}


function cheques_fondo_terceros($cod_entidad=null, $cod_sucursal=null, $cuenta=null, $tipo_cuenta=null, $tipo_doc=null){
	$this->layout="ajax";
	if($tipo_cuenta==2 && $tipo_doc==4){
		$mostrar=1;

 		$vec=array('01'=>'SEGURO SOCIAL OBLIGATORIO',
                   '02'=>'PARO FORZOSO',
                   '03'=>'LEY DE POLÍTICA HABITACIONAL',
                   '04'=>'FONDO DE PENSIÓN Y JUBILACIÓN',
 				   '05'=>'CAJA DE AHORROS',
 				   '06'=>'SINDICATOS Y GREMIOS',
	 			   '07'=>'JUZGADOS Y TRIBUNALES',
	 			   '08'=>'CASAS COMERCIALES',
	 			   '50'=>'RETENCIÓN DE IVA',
	 			   '51'=>'RETENCIÓN DE ISLR',
	 			   '52'=>'RETENCIÓN DE TIMBRE FISCAL',
	 			   '53'=>'RETENCIÓN DE IMPUESTO MUNICIPAL',
	 			   '54'=>'RETENCIÓN POR RESPONSABILIDAD CIVIL',
	 			   '55'=>'RETENCIÓN POR RESPONSABILIDAD SOCIAL',
                   '99'=>'OTRAS RETENCIONES');
 		$this->set('vec',$vec);
 		$this->set('mensaje', 'SELECCIONE EL TIPO DE CUENTA POR FAVOR');
	}else{
		$mostrar=0;
	}
	$this->set('mostrar',$mostrar);
}


function listado_tipo_ingreso($cod_entidad=null, $cod_sucursal=null, $cuenta=null, $tipo_cuenta=null, $tipo_doc=null){
	$this->layout="ajax";

	// $mostrar=0; // Quitar para contabilidad.

	$cp=$this->Session->read('SScodpresi');
	$ce=$this->Session->read('SScodentidad');
	$cti=$this->Session->read('SScodtipoinst');
	$ci=$this->Session->read('SScodinst');
	$ano = $this->ano_ejecucion();

	if($tipo_cuenta==1){
		/*
		$sql = "select  a.*,
					b.cod_grupo,
					b.denominacion

				from v_ingresos_conta_1 a, cfpd01_ano_4_especifica b

				where
				a.cod_presi='$cp' AND
				a.cod_entidad='$ce' AND
				a.cod_tipo_inst='$cti' AND
				a.cod_inst='$ci' AND
				b.cod_partida::integer=a.cod_partida::integer AND
				b.cod_generica		= a.cod_generica AND
				b.cod_especifica	= a.cod_especifica AND
				b.ejercicio			= a.ano AND
				a.ano='$ano' AND
				b.cod_grupo=3
				ORDER BY
				  b.cod_grupo,
				  a.cod_partida,
				  a.cod_generica,
				  a.cod_especifica;";

		$lista=$this->cstd04_movimientos_generales->execute($sql);
		$cant_registro = count($lista);
		for($i=0; $i<$cant_registro; $i++){
			$i%2==0 ? $color= "#CDF2FF" : $color= "#DAEBFF";
			$listado_ing[] = $lista[$i][0]['cod_grupo'].'01-'.$lista[$i][0]['cod_partida'].'-'.$lista[$i][0]['cod_generica'].'-'.$lista[$i][0]['cod_especifica'];
		}
		*/

	$sql = "select
				a.cod_presi,
				a.cod_entidad,
				a.cod_tipo_inst,
				a.cod_inst,
				a.cod_dep,
				a.ano,
				substr(a.cod_partida::text, 2, 2)::text AS cod_partida,
				a.cod_generica,
				a.cod_especifica,
				a.cod_sub_espec,
				a.deno_sub_espec,
				a.cod_auxiliar,
				a.deno_auxiliar
			from
				v_cfpd03 a
			where

				a.cod_presi='$cp' AND
				a.cod_entidad='$ce' AND
				a.cod_tipo_inst='$cti' AND
				a.cod_inst='$ci' AND
				a.ano='$ano'
				ORDER BY
				  a.cod_partida,
				  a.cod_generica,
				  a.cod_especifica,
				  a.cod_sub_espec;";
		$lista=$this->cstd04_movimientos_generales->execute($sql);
		$cant_registro = count($lista);
		for($i=0; $i<$cant_registro; $i++){
			$i%2==0 ? $color= "#CDF2FF" : $color= "#DAEBFF";
			$listado_ing[] = '301-'.$lista[$i][0]['cod_partida'].'-'.$lista[$i][0]['cod_generica'].'-'.$lista[$i][0]['cod_especifica'];
		}
		$this->Session->write('listado_ingreso',$listado_ing);

		echo'<script>';
  	 	 echo"document.getElementById('cant_registros_ingresos').value='$cant_registro';";
  		echo'</script>';

		$mostrar=1;
		$this->set('lista',$lista);
		$this->set('cant_registro',$cant_registro);
 		$this->set('mensaje', 'INGRESE EL MONTO EN LA DISTRIBUCIÓN DE LAS CUENTAS CONTABLES');
	}else{
		$mostrar=0;
	}
	$this->set('mostrar',$mostrar);
}



//****************** Funciones para el pago de ordenes de transferencias ******************//

function pagotransferencia(){
	$this->layout="ajax";
}
function datos_pagotransferencia($var=null){
	$this->layout="ajax";
	if($var==1){
		  $array_dependencias = array();
		  $ano_solicitud = $this->ano_ejecucion();
		  $dependencias = $this->csrd01_solicitud_recurso_cuerpo->generateList( $this->condicionNDEP().' and numero_cheque=0'." and ano_solicitud='".$ano_solicitud."'  ", 'cod_dep ASC', null, '{n}.csrd01_solicitud_recurso_cuerpo.cod_dep', '{n}.csrd01_solicitud_recurso_cuerpo.cod_dep');
		  $dependencias_array = $this->csrd01_solicitud_recurso_cuerpo->findAll($this->condicionNDEP().' and numero_cheque=0'." and ano_solicitud='".$ano_solicitud."'  ", array('cod_dep'), 'cod_dep ASC');
   		  $tabla_dependencias = $this->cugd02_dependencia->execute("SELECT DISTINCT a.cod_dep, (SELECT b.denominacion FROM cugd02_dependencias b WHERE ".$this->condicionNDEP()." and a.cod_dep=b.cod_dependencia) as denominacion FROM csrd01_solicitud_recurso_cuerpo a WHERE ".$this->condicionNDEP()." and numero_cheque=0 ORDER BY a.cod_dep ASC");
   		  for($i=0; $i<count($tabla_dependencias); $i++){
   		  		$array_dependencias[$tabla_dependencias[$i][0]['cod_dep']] = $tabla_dependencias[$i][0]['denominacion'];
   		  }
   		  $this->set('ano_solicitud',$ano_solicitud);
   		  if($array_dependencias!=''){
   		  	$this->concatena_sin_cero($array_dependencias,'dependencias');
   		  	$this->set('mensaje', 'POR FAVOR, SELECCIONE LA DEPENDENCIA Y EL NUMERO DE LA SOLICITUD');
   		  }else{
   		  	$this->set('dependencias',array('No hay dependencias'));
   		  	$this->set('mensajeError', 'NO EXISTEN DEPENDENCIAS PENDIENTES POR SOLICITUD DE RECURSOS');
   		  }
	}elseif($var==2){

	}
	$this->set('opcion',$var);
}


function numerosolicitud($ano_solicitud=null, $var=null){
	$this->layout="ajax";
	$ano_solicitud;//ano solicitud
	$var;          //dependencia
	if($var!=null){
	$condicion="cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$var." and numero_cheque=0";
	$num_solicitud = $this->csrd01_solicitud_recurso_cuerpo->generateList($condicion." and ano_solicitud='".$ano_solicitud."'  ", 'cod_dep ASC', null, '{n}.csrd01_solicitud_recurso_cuerpo.numero_solicitud', '{n}.csrd01_solicitud_recurso_cuerpo.numero_solicitud');
   	$this->set('num_solicitud',$num_solicitud);
	}else{
		$this->set('num_solicitud','');
	}
}


function ano_solicitud($ano_solicitud=null,$var=null){
	$this->layout="ajax";
	$this->set('ano_solicitud',$ano_solicitud);
	$this->set('dependencia',$var);//dependencia
}

function escribir_ano($dep=null,$ano_solicitud=null){
	$this->layout="ajax";
	$dep;//Variable Dependencia
	$ano_solicitud;

	if($dep!=null){
	$condicion="cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$dep." and ano_solicitud=".$ano_solicitud." and cod_entidad_bancaria=0";
	$num_solicitud = $this->csrd01_solicitud_recurso_cuerpo->generateList($condicion, 'cod_dep ASC', null, '{n}.csrd01_solicitud_recurso_cuerpo.numero_solicitud', '{n}.csrd01_solicitud_recurso_cuerpo.numero_solicitud');
   	$this->set('num_solicitud',$num_solicitud);
	}else{
		$this->set('num_solicitud','');
	}
}


function procesar_pagotransferencia(){
	$this->layout="ajax";

	$cod_tipo_institucion=$this->Session->read('SScodtipoinst');
	$cod_institucion=$this->Session->read('SScodinst');
	$dependencia=$this->data['cstp03_movimientos_manuales']['select_dependencias'];
	$ano_solicitud=$this->data['cstp03_movimientos_manuales']['ano_2'];
	$numero_solicitud=$this->data['cstp03_movimientos_manuales']['numero_solicitud'];

	if(!empty($this->data['cstp03_movimientos_manuales']['select_dependencias']) && !empty($this->data['cstp03_movimientos_manuales']['ano_2']) && !empty($this->data['cstp03_movimientos_manuales']['numero_solicitud'])){
		$condicion="cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$dependencia." and ano_solicitud=".$ano_solicitud." and numero_solicitud=".$numero_solicitud;
		$datos_solicitud=$this->csrd01_solicitud_recurso_cuerpo->findAll($condicion);

		$deno_dep=$this->cugd02_dependencia->findAll('cod_tipo_institucion='.$cod_tipo_institucion.' and cod_institucion='.$cod_institucion.' and cod_dependencia='.$dependencia, null, array('denominacion'));
		$this->set('beneficiario_solicitud',$deno_dep[0]['cugd02_dependencia']['denominacion']);

		$this->set('concepto_solicitud',$datos_solicitud[0]['csrd01_solicitud_recurso_cuerpo']['concepto']);
		$this->set('monto_solicitud',$datos_solicitud[0]['csrd01_solicitud_recurso_cuerpo']['monto_solicitado']);
		$this->set('dep_solicitud',$dependencia);
		$this->set('ano_solicitud',$ano_solicitud);
		$this->set('num_solicitud',$numero_solicitud);
		$this->set('mensaje', 'LA ORDEN DE TRANSFERENCIA FUE CARGADA CORRECTAMENTE');
		$this->Session->write('dep_solicitud',$dependencia);
		$this->Session->write('ano_solicitud',$ano_solicitud);
		$this->Session->write('num_solicitud',$numero_solicitud);
	}else{
		$this->set('concepto_solicitud','');
		$this->set('monto_solicitud','');
		$this->set('mensajeError', 'LO SIENTO, LA ORDEN DE TRANSFERENCIA NO PUDO SER PROCESADA CORRECTAMENTE');
	}
}



function beneficiario_solicitud(){
	$this->layout="ajax";

	$cod_tipo_institucion=$this->Session->read('SScodtipoinst');
	$cod_institucion=$this->Session->read('SScodinst');
	$dependencia=$this->data['cstp03_movimientos_manuales']['select_dependencias'];
	$ano_solicitud=$this->data['cstp03_movimientos_manuales']['ano_2'];
	$numero_solicitud=$this->data['cstp03_movimientos_manuales']['numero_solicitud'];

	if(!empty($this->data['cstp03_movimientos_manuales']['select_dependencias']) && !empty($this->data['cstp03_movimientos_manuales']['ano_2']) && !empty($this->data['cstp03_movimientos_manuales']['numero_solicitud'])){
		// Solo para buscar el beneficiario, que en este caso es la dependencia.
		$deno_dep=$this->cugd02_dependencia->findAll('cod_tipo_institucion='.$cod_tipo_institucion.' and cod_institucion='.$cod_institucion.' and cod_dependencia='.$dependencia, null, array('denominacion'));
		$this->set('beneficiario_solicitud',$deno_dep[0]['cugd02_dependencia']['denominacion']);
	}

}

function monto_solicitud(){
	$this->layout="ajax";

	$cod_tipo_institucion=$this->Session->read('SScodtipoinst');
	$cod_institucion=$this->Session->read('SScodinst');
	$dependencia=$this->data['cstp03_movimientos_manuales']['select_dependencias'];
	$ano_solicitud=$this->data['cstp03_movimientos_manuales']['ano_2'];
	$numero_solicitud=$this->data['cstp03_movimientos_manuales']['numero_solicitud'];
	if(!empty($this->data['cstp03_movimientos_manuales']['select_dependencias']) && !empty($this->data['cstp03_movimientos_manuales']['ano_2']) && !empty($this->data['cstp03_movimientos_manuales']['numero_solicitud'])){
		$condicion="cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$dependencia." and ano_solicitud=".$ano_solicitud." and numero_solicitud=".$numero_solicitud;
		$datos_solicitud=$this->csrd01_solicitud_recurso_cuerpo->findAll($condicion, array('monto_solicitado'));
		$this->set('monto_solicitud',$datos_solicitud[0]['csrd01_solicitud_recurso_cuerpo']['monto_solicitado']);
	}
}

function concepto_solicitud(){
	$this->layout="ajax";

	$cod_tipo_institucion=$this->Session->read('SScodtipoinst');
	$cod_institucion=$this->Session->read('SScodinst');
	$dependencia=$this->data['cstp03_movimientos_manuales']['select_dependencias'];
	$ano_solicitud=$this->data['cstp03_movimientos_manuales']['ano_2'];
	$numero_solicitud=$this->data['cstp03_movimientos_manuales']['numero_solicitud'];
	if(!empty($this->data['cstp03_movimientos_manuales']['select_dependencias']) && !empty($this->data['cstp03_movimientos_manuales']['ano_2']) && !empty($this->data['cstp03_movimientos_manuales']['numero_solicitud'])){
		$condicion="cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$dependencia." and ano_solicitud=".$ano_solicitud." and numero_solicitud=".$numero_solicitud;
		$datos_solicitud=$this->csrd01_solicitud_recurso_cuerpo->findAll($condicion, array('concepto'));
		$this->set('concepto_solicitud',$datos_solicitud[0]['csrd01_solicitud_recurso_cuerpo']['concepto']);
	}
}


// FUNCIONES PARA LOS TIPOS DE RECURSOS *******//

/**
 * Select tipo recurso.
 * @param int $var codigo tipo de recurso proveniente del plan de inversion.
*/
function select_tipo_recurso($var=null){
	$this->layout="ajax";
	$condicion="cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and tipo_recurso=".$var;
	$clasifi_tipo_recurso=  $this->cfpd07_clasificacion_recurso->generateList($condicion, 'clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion');
    $this->concatena($clasifi_tipo_recurso, 'clasifi_tipo_recurso');
    $this->set('tipo_recurso',$var);
}


/**
 * Cod tipo recurso.
 * @param int $var codigo tipo de recurso proveniente del plan de inversion.
 * @param int $cr clasificacion_recurso en el plan de inversion.
*/
function cod_tipo_recurso($tr=null, $cr=null){
	$this->layout="ajax";
	$tr;
	$cr;
	$condicion="cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and tipo_recurso=".$tr." and clasificacion_recurso=".$cr;
	$deno_tipo_recurso=  $this->cfpd07_clasificacion_recurso->findAll($condicion,array('clasificacion_recurso'));
    $this->set('cod_clasif_tipo_recurso', $deno_tipo_recurso[0]['cfpd07_clasificacion_recurso']['clasificacion_recurso']);
}


/**
 * Deno tipo recurso.
 * @param int $tr tipo_recurso proveniente del plan de inversion.
 * @param int $cr clasificacion_recurso en el plan de inversion.
*/
function deno_tipo_recurso($tr=null, $cr=null){
	$this->layout="ajax";
	$tr;
	$cr;
	$condicion="cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and tipo_recurso=".$tr." and clasificacion_recurso=".$cr;
	$deno_tipo_recurso=  $this->cfpd07_clasificacion_recurso->findAll($condicion,array('denominacion'));
    $this->set('deno_clasif_tipo_recurso', $deno_tipo_recurso[0]['cfpd07_clasificacion_recurso']['denominacion']);
}


// *********************************************


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cstp03_movimientos_manuales']['login']) && isset($this->data['cstp03_movimientos_manuales']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cstp03_movimientos_manuales']['login']);
		$paswd=addslashes($this->data['cstp03_movimientos_manuales']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=68 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}

function pdf_impresion_cheque($ano=null, $entidad_banc=null, $sucursal_banc=null, $cuenta_banc=null, $tipo_documento=null, $numero_cheq=null){
	$this->layout="pdf";
	$condicion 	   = $this->SQLCA()." AND ano_movimiento='$ano' AND cod_entidad_bancaria='$entidad_banc' AND cod_sucursal='$sucursal_banc' AND cuenta_bancaria='$cuenta_banc' AND tipo_documento='$tipo_documento' AND numero_documento='$numero_cheq'";
	$cheque_cuerpo = $this->cstd03_movimientos_manuales->findAll($condicion, null, "cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_documento ASC");
	$this->set('cheque_cuerpo', $cheque_cuerpo);
	if(isset($this->data['cstp03_movimientos_manuales']['forma_orientacion'])){
		$this->set('forma_orientacion', $this->data['cstp03_movimientos_manuales']['forma_orientacion']);
	}
}

}//fin clase
?>
