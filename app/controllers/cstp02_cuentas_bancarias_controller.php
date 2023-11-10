<?php
/*
 * Creado el 06/12/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 04:42:35 AM
 */
 class Cstp02cuentasbancariasController extends AppController {
   var $name = 'cstp02_cuentas_bancarias';
   var $uses = array('v_cstd01_bancos','v_cstd01_sucursales', 'v_cuentas_bancarias', 'cstd01_sucursales_bancarias','cstd01_entidades_bancarias','usuario','cstd04_movimientos_generales','cstd02_cuentas_bancarias', 'ccfd01_cuenta', 'ccfd01_tipo', 'ccfd01_subcuenta', 'ccfd01_division', 'ccfd01_subdivision');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');


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
 	 echo'				<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                         </script>';

 }

function ss($i){
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
         $sql_re = "cod_presi=".$this->ss(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->ss(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->ss(3)."  and ";
         $sql_re .= "cod_inst=".$this->ss(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->ss(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->ss(5)." ";
         }
         return $sql_re;
    }//fin funcion SQLCA

 function concatena_superior($vector1=null, $nomVar=null, $extra=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){


			if($extra!=null){

             $cod[$x] = $this->zero($x).' - '.$y;

			}else{

             $cod[$x] = $this->zero($x).' - '.$y;

			}
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

function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zeros($x).' - '.$y;

		}
		$this->set($nomVar, $cod);
	}
}
function in(){

	$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo');
}


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

 function index(){
 	$this->layout ="ajax";
 	$this->set('tipo_en',array());
 	$this->set('tipo_su',array());
 	$this->Session->delete('s_ent');
 	//$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo_en');
 	$this->set('tipo_en', $this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'));
	$lista = $this->ccfd01_tipo->generateList($conditions = $this->SQLCA(), $order = 'cod_tipo_cuenta', $limit = null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion');
	$this->concatena($lista, 'tipo_cuenta');
	//$this->concatena($this->cstd01_sucursales_bancarias->generateList('',' cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion'), 'tipo_su');
	$this->set('enable', 'disabled');
 	}

function select($select=null,$var=null) { //select de deposito debe
	$this->layout = "ajax";
	/**
	 * cod_1 : tipo_cuenta
	 * cod_2 : cuenta
	 * cod_3 : sub_cuenta
	 * cod_4 : division
	 * cod_5 : subdivision
	 */
if(isset($var) && !empty($var) && $var!=''){
    $cond =$this->SQLCA();
	switch($select){
		case 'cuenta':
		  $this->set('SELECT','sub_cuenta');
		  $this->set('codigo','cuenta');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('cod_1',$var);
		  $cond .=" and cod_tipo_cuenta=".$var;
		  $lista=  $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'sub_cuenta':
		  $this->set('SELECT','division');
		  $this->set('codigo','sub_cuenta');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 =  $this->Session->read('cod_1');

		  $this->Session->write('cod_2',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$var;
		  $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'division':
		  $this->set('SELECT','subdivision');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		   $this->set('n',4);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');

		  $this->Session->write('cod_3',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$var;
		  $lista=  $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'subdivision':
		  $this->set('SELECT','');
		  $this->set('codigo','subdivision');
		  $this->set('seleccion','');
		   $this->set('n',5);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');

		  $this->Session->write('cod_4',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$cod_3." and cod_division=".$var;
		  $lista=  $this->ccfd01_subdivision->generateList($cond, 'cod_subdivision ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');
          $this->concatena($lista, 'vector');
		break;
	}
	}else{
		echo "";
	}
}//fin select depositos	debe

function select2($select=null,$var=null) { //select de deposito haber
	$this->layout = "ajax";
	/**
	 * cod_1 : tipo_cuenta
	 * cod_2 : cuenta
	 * cod_3 : sub_cuenta
	 * cod_4 : division
	 * cod_5 : subdivision
	 */
if(isset($var) && !empty($var) && $var!=''){
    $cond =$this->SQLCA();
	switch($select){
		case 'cuenta':
		  $this->set('SELECT','sub_cuenta');
		  $this->set('codigo','cuenta');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('cod_1',$var);
		  $cond .=" and cod_tipo_cuenta=".$var;
		  $lista=  $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'sub_cuenta':
		  $this->set('SELECT','division');
		  $this->set('codigo','sub_cuenta');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 =  $this->Session->read('cod_1');

		  $this->Session->write('cod_2',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$var;
		  $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'division':
		  $this->set('SELECT','subdivision');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		   $this->set('n',4);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');

		  $this->Session->write('cod_3',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$var;
		  $lista=  $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'subdivision':
		  $this->set('SELECT','');
		  $this->set('codigo','subdivision');
		  $this->set('seleccion','');
		   $this->set('n',5);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');

		  $this->Session->write('cod_4',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$cod_3." and cod_division=".$var;
		  $lista=  $this->ccfd01_subdivision->generateList($cond, 'cod_subdivision ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');
          $this->concatena($lista, 'vector');
		break;
	}
	}else{
		echo "";
	}
}//fin select depositos	haber

function select3($select=null,$var=null) { //select de nc debe
	$this->layout = "ajax";
	/**
	 * cod_1 : tipo_cuenta
	 * cod_2 : cuenta
	 * cod_3 : sub_cuenta
	 * cod_4 : division
	 * cod_5 : subdivision
	 */
if(isset($var) && !empty($var) && $var!=''){
    $cond =$this->SQLCA();
	switch($select){
		case 'cuenta':
		  $this->set('SELECT','sub_cuenta');
		  $this->set('codigo','cuenta');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('cod_1',$var);
		  $cond .=" and cod_tipo_cuenta=".$var;
		  $lista=  $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'sub_cuenta':
		  $this->set('SELECT','division');
		  $this->set('codigo','sub_cuenta');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 =  $this->Session->read('cod_1');

		  $this->Session->write('cod_2',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$var;
		  $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'division':
		  $this->set('SELECT','subdivision');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		   $this->set('n',4);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');

		  $this->Session->write('cod_3',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$var;
		  $lista=  $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'subdivision':
		  $this->set('SELECT','');
		  $this->set('codigo','subdivision');
		  $this->set('seleccion','');
		   $this->set('n',5);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');

		  $this->Session->write('cod_4',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$cod_3." and cod_division=".$var;
		  $lista=  $this->ccfd01_subdivision->generateList($cond, 'cod_subdivision ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');
          $this->concatena($lista, 'vector');
		break;
	}
	}else{
		echo "";
	}
}//fin select nc debe

function select4($select=null,$var=null) { //select de nota de credito haber
	$this->layout = "ajax";
	/**
	 * cod_1 : tipo_cuenta
	 * cod_2 : cuenta
	 * cod_3 : sub_cuenta
	 * cod_4 : division
	 * cod_5 : subdivision
	 */
if(isset($var) && !empty($var) && $var!=''){
    $cond =$this->SQLCA();
	switch($select){
		case 'cuenta':
		  $this->set('SELECT','sub_cuenta');
		  $this->set('codigo','cuenta');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('cod_1',$var);
		  $cond .=" and cod_tipo_cuenta=".$var;
		  $lista=  $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'sub_cuenta':
		  $this->set('SELECT','division');
		  $this->set('codigo','sub_cuenta');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 =  $this->Session->read('cod_1');

		  $this->Session->write('cod_2',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$var;
		  $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'division':
		  $this->set('SELECT','subdivision');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		   $this->set('n',4);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');

		  $this->Session->write('cod_3',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$var;
		  $lista=  $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'subdivision':
		  $this->set('SELECT','');
		  $this->set('codigo','subdivision');
		  $this->set('seleccion','');
		   $this->set('n',5);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');

		  $this->Session->write('cod_4',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$cod_3." and cod_division=".$var;
		  $lista=  $this->ccfd01_subdivision->generateList($cond, 'cod_subdivision ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');
          $this->concatena($lista, 'vector');
		break;
	}
	}else{
		echo "";
	}
}//fin select nota de credito haber

function select5($select=null,$var=null) { //select de cheque debe
	$this->layout = "ajax";
	/**
	 * cod_1 : tipo_cuenta
	 * cod_2 : cuenta
	 * cod_3 : sub_cuenta
	 * cod_4 : division
	 * cod_5 : subdivision
	 */
if(isset($var) && !empty($var) && $var!=''){
    $cond =$this->SQLCA();
	switch($select){
		case 'cuenta':
		  $this->set('SELECT','sub_cuenta');
		  $this->set('codigo','cuenta');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('cod_1',$var);
		  $cond .=" and cod_tipo_cuenta=".$var;
		  $lista=  $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'sub_cuenta':
		  $this->set('SELECT','division');
		  $this->set('codigo','sub_cuenta');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 =  $this->Session->read('cod_1');

		  $this->Session->write('cod_2',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$var;
		  $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'division':
		  $this->set('SELECT','subdivision');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		   $this->set('n',4);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');

		  $this->Session->write('cod_3',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$var;
		  $lista=  $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'subdivision':
		  $this->set('SELECT','');
		  $this->set('codigo','subdivision');
		  $this->set('seleccion','');
		   $this->set('n',5);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');

		  $this->Session->write('cod_4',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$cod_3." and cod_division=".$var;
		  $lista=  $this->ccfd01_subdivision->generateList($cond, 'cod_subdivision ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');
          $this->concatena($lista, 'vector');
		break;
	}
	}else{
		echo "";
	}
}//fin select cheque debe

function select6($select=null,$var=null) { //select de cheques haber
	$this->layout = "ajax";
	/**
	 * cod_1 : tipo_cuenta
	 * cod_2 : cuenta
	 * cod_3 : sub_cuenta
	 * cod_4 : division
	 * cod_5 : subdivision
	 */
if(isset($var) && !empty($var) && $var!=''){
    $cond =$this->SQLCA();
	switch($select){
		case 'cuenta':
		  $this->set('SELECT','sub_cuenta');
		  $this->set('codigo','cuenta');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('cod_1',$var);
		  $cond .=" and cod_tipo_cuenta=".$var;
		  $lista=  $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'sub_cuenta':
		  $this->set('SELECT','division');
		  $this->set('codigo','sub_cuenta');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 =  $this->Session->read('cod_1');

		  $this->Session->write('cod_2',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$var;
		  $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'division':
		  $this->set('SELECT','subdivision');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		   $this->set('n',4);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');

		  $this->Session->write('cod_3',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$var;
		  $lista=  $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'subdivision':
		  $this->set('SELECT','');
		  $this->set('codigo','subdivision');
		  $this->set('seleccion','');
		   $this->set('n',5);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');

		  $this->Session->write('cod_4',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$cod_3." and cod_division=".$var;
		  $lista=  $this->ccfd01_subdivision->generateList($cond, 'cod_subdivision ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');
          $this->concatena($lista, 'vector');
		break;
	}
	}else{
		echo "";
	}
}//fin select cheques haber

function select7($select=null,$var=null) { //select de nota de debito debe
	$this->layout = "ajax";
	/**
	 * cod_1 : tipo_cuenta
	 * cod_2 : cuenta
	 * cod_3 : sub_cuenta
	 * cod_4 : division
	 * cod_5 : subdivision
	 */
if(isset($var) && !empty($var) && $var!=''){
    $cond =$this->SQLCA();
	switch($select){
		case 'cuenta':
		  $this->set('SELECT','sub_cuenta');
		  $this->set('codigo','cuenta');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('cod_1',$var);
		  $cond .=" and cod_tipo_cuenta=".$var;
		  $lista=  $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'sub_cuenta':
		  $this->set('SELECT','division');
		  $this->set('codigo','sub_cuenta');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 =  $this->Session->read('cod_1');

		  $this->Session->write('cod_2',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$var;
		  $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'division':
		  $this->set('SELECT','subdivision');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		   $this->set('n',4);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');

		  $this->Session->write('cod_3',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$var;
		  $lista=  $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'subdivision':
		  $this->set('SELECT','');
		  $this->set('codigo','subdivision');
		  $this->set('seleccion','');
		   $this->set('n',5);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');

		  $this->Session->write('cod_4',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$cod_3." and cod_division=".$var;
		  $lista=  $this->ccfd01_subdivision->generateList($cond, 'cod_subdivision ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');
          $this->concatena($lista, 'vector');
		break;
	}
	}else{
		echo "";
	}
}//fin select nota de debito debe

function select8($select=null,$var=null) { //select de notas de debito haber
	$this->layout = "ajax";
	/**
	 * cod_1 : tipo_cuenta
	 * cod_2 : cuenta
	 * cod_3 : sub_cuenta
	 * cod_4 : division
	 * cod_5 : subdivision
	 */
if(isset($var) && !empty($var) && $var!=''){
    $cond =$this->SQLCA();
	switch($select){
		case 'cuenta':
		  $this->set('SELECT','sub_cuenta');
		  $this->set('codigo','cuenta');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('cod_1',$var);
		  $cond .=" and cod_tipo_cuenta=".$var;
		  $lista=  $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'sub_cuenta':
		  $this->set('SELECT','division');
		  $this->set('codigo','sub_cuenta');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 =  $this->Session->read('cod_1');

		  $this->Session->write('cod_2',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$var;
		  $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'division':
		  $this->set('SELECT','subdivision');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		   $this->set('n',4);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');

		  $this->Session->write('cod_3',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$var;
		  $lista=  $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'subdivision':
		  $this->set('SELECT','');
		  $this->set('codigo','subdivision');
		  $this->set('seleccion','');
		   $this->set('n',5);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');

		  $this->Session->write('cod_4',$var);
		  $cond .=" and cod_tipo_cuenta=".$cod_1." and cod_cuenta=".$cod_2." and  cod_subcuenta=".$cod_3." and cod_division=".$var;
		  $lista=  $this->ccfd01_subdivision->generateList($cond, 'cod_subdivision ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');
          $this->concatena($lista, 'vector');
		break;
	}
	}else{
		echo "";
	}
}//fin select notas de debito haber

function codEntidad($cod_entidad=null){
	$this->layout ="ajax";
	if($cod_entidad!=null){
		$this->set('cod_entidad', mascara($cod_entidad, 4));
	}

}

function deno_entidad($cod_entidad=null){
	$this->layout ="ajax";
	if($cod_entidad!=null){
		$deno_entidad = $this->cstd01_entidades_bancarias->field('cstd01_entidades_bancarias.denominacion', $conditions = "cstd01_entidades_bancarias.cod_entidad_bancaria='$cod_entidad'", $order ="cod_entidad_bancaria ASC");
		$this->set('deno_entidad', $deno_entidad);
	}
}

function selec_sucursal($cod_entidad=null){
	$this->layout ="ajax";
	if($cod_entidad!=null){
		$this->set('cod_entidad', $cod_entidad);
		$lista = $this->cstd01_sucursales_bancarias->generateList('cod_entidad_bancaria='.$cod_entidad,' cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
		$this->set('sucursal', $lista);
	}
}

function codSucursal($cod_entidad=null, $cod_sucursal=null){
	$this->layout ="ajax";
	if($cod_entidad!=null && $cod_sucursal!=null){
		$this->set('cod_sucursal', mascara($cod_sucursal, 4));

	}
}

function deno_sucursal($cod_entidad=null, $cod_sucursal=null){
	$this->layout ="ajax";
	if($cod_entidad!=null && $cod_sucursal!=null){
		$deno_sucursal = $this->cstd01_sucursales_bancarias->field('cstd01_sucursales_bancarias.denominacion', $conditions = "cstd01_sucursales_bancarias.cod_entidad_bancaria='$cod_entidad' and cstd01_sucursales_bancarias.cod_sucursal='$cod_sucursal'", $order ="cod_entidad_bancaria, cod_sucursal ASC");
		$this->set('deno_sucursal', $deno_sucursal);
	}
}

function cuenta($cod_entidad=null, $cod_sucursal=null){
	$this->layout ="ajax";
	if($cod_entidad!=null && $cod_sucursal!=null){
		$this->set('cod_sucursal', mascara($cod_sucursal, 4));
		$this->set('cod_entidad', mascara($cod_entidad, 4));

	}
}



 function select_entidad($id = null){
 	$this->layout ="ajax";

 	$this->set('tipo',Array());
 	$this->set('sel_en',$id);
 	$this->set('tipo_su',array());
	$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo_en');
	$busca=$this->SQLCA()." and cod_entidad_bancaria=".$id;
	$this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'tipo_su');
 	$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$id));
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->Session->write('s_ent',$id);

 }

  function select_sucursal($id = null){
 	$this->layout ="ajax";
 	$this->set('tipo',Array());
 	$this->set('sel_en',$this->Session->read('s_ent'));
 	$this->set('sel_su',$id);
 	$this->set('tipo_su',array());
 	$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo_en');
	$busca=$this->SQLCA()." and cod_entidad_bancaria=".$this->Session->read('s_ent');
	$this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'tipo_su');

 			$this->set('otros', false);
 	 		$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$this->Session->read('s_ent')));
 	 		$this->set('datos_su', $this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$this->Session->read('s_ent').' and cod_sucursal='.$id));

 	 		$this->set('enable2', 'disabled');
 			$this->set('enable', 'enable');
 			$this->set('read', 'readonly');

 }

 function guardar(){
	$this->layout ="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad_federal = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

 	$this->set('enable2', 'disabled');
 	$this->set('enable', 'enable');
 	$this->set('enable', 'disabled');
	$cod_entidad = $this->data['cstp02_cuentas_bancarias']['codigo_entidad'];
	$cod_sucursal = $this->data['cstp02_cuentas_bancarias']['codigo_sucursal'];

	$pre = $this->data['cstp02_cuentas_bancarias']['pre_cuenta'];
	$cuenta_bancaria = $pre.$this->data['cstp02_cuentas_bancarias']['cuenta_bancaria'];
	$fecha_apertura = $this->Cfecha($this->data['cstp02_cuentas_bancarias']['fecha_apertura'], 'A-M-D');
	$concepto_manejo =$this->data['cstp02_cuentas_bancarias']['concepto_manejo'];
	$responsable_manejo =$this->data['cstp02_cuentas_bancarias']['responsable_manejo'];
	$cargo_responable =$this->data['cstp02_cuentas_bancarias']['cargo_responable'];
	$radio_tipocuenta = $this->data['cstp02_cuentas_bancarias']['radio_tipocuenta'];// Indica 1.-si pertenece a la institucion 2.-si es de terceros

	$mov_dia_depositos = 0;
	$mov_dia_nota_credito = 0;
	$mov_dia_nota_debito = 0;
	$mov_dia_monto_cheque = 0;

	$mov_mes_depositos = 0;
	$mov_mes_nota_credito = 0;
	$mov_mes_nota_debito = 0;
	$mov_mes_monto_cheque = 0;

	$mov_ejer_depositos = 0;
	$mov_ejer_nota_credito = 0;
	$mov_ejer_nota_debito = 0;
	$mov_ejer_monto_cheque = 0;

	$cheque_emitir = 0;
	$cheque_custodia = 0;
	$cheque_transito = 0;
	$cheque_pagado = 0;

	$disp_saldo_anterior = 0;
	$disp_saldo_mes_anterior = 0;
	$disp_libro = 0;
	$disp_real = 0;

	$consulta="select * from cstd02_cuentas_bancarias where ".$this->SQLCA()." and cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria'";
	$sql="insert into cstd02_cuentas_bancarias values('".$this->ss(1)."','".$this->ss(2)."','".$this->ss(3)."','".$this->ss(4)."','".$this->ss(5)."','$cod_entidad','$cod_sucursal','$cuenta_bancaria'," .
			"'$fecha_apertura','$concepto_manejo','$responsable_manejo','$cargo_responable'," .
			"'$mov_dia_depositos','$mov_mes_depositos','$mov_ejer_depositos',".
			"'$mov_dia_nota_credito','$mov_mes_nota_credito','$mov_ejer_nota_credito',".
			"'$mov_dia_nota_debito','$mov_mes_nota_debito','$mov_ejer_nota_debito',".
			"'$mov_dia_monto_cheque','$mov_mes_monto_cheque','$mov_ejer_monto_cheque',".
			"'$cheque_emitir','$cheque_custodia','$cheque_transito','$cheque_pagado',".
			"'$disp_saldo_anterior','$disp_saldo_mes_anterior','$disp_libro','$disp_real','$radio_tipocuenta')";

	if($this->cstd02_cuentas_bancarias->execute($consulta)){
		$this->set('errorMessage','El n&uacute;mero de cuenta '.$cuenta_bancaria.' ya existe');
		$this->consultar2($cod_entidad,$cod_sucursal,$cuenta_bancaria,$pagina=null);
		$this->render("consultar2");
	}else{

		//$this->cstd02_cuentas_bancarias->execute("BEGIN;");
		//$this->cstd02_cuentas_bancarias->execute("COMMIT;");
		//$this->cstd02_cuentas_bancarias->execute("ROLLBACK;");

	$this->cstd02_cuentas_bancarias->execute("BEGIN;");

		if($this->cstd02_cuentas_bancarias->execute($sql)>1){

			if($radio_tipocuenta == 1){
				$cod_tipo_cuenta = 1;
				$cod_cuenta      = 102;
				$cod_subcuenta   = 002;
				$cod_division    = $cod_entidad;
				$cod_subdivision = '';
				$denominacion=$concepto_manejo.' - '.$cuenta_bancaria;
				$concepto='';

			}elseif($radio_tipocuenta == 2){
				$cod_tipo_cuenta = 1;
				$cod_cuenta      = 132;
				$cod_subcuenta   = 001;
				$cod_division    = $cod_entidad;
				$cod_subdivision = '';
				$denominacion=$concepto_manejo.' - '.$cuenta_bancaria;
				$concepto='';

				$sql_consulta_division="cod_presi='$cod_presi' AND cod_entidad='$cod_entidad_federal' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='1' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division'";
				if($this->ccfd01_division->findCount($sql_consulta_division)==0){// Se verifica que no exista este registro en la tabla de divisiones.
					$ent=$this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$cod_entidad,'denominacion','cod_entidad_bancaria ASC');
					$deno_entidad = $ent[0]['cstd01_entidades_bancarias']['denominacion'];

					$sql_insert_division="INSERT INTO ccfd01_division VALUES ('$cod_presi', '$cod_entidad_federal', '$cod_tipo_inst', '$cod_inst', '1', '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$deno_entidad', '$concepto')";
					$result = $this->ccfd01_division->execute($sql_insert_division);
				}

			}

			$cond_subdivision="cod_presi='$cod_presi' AND cod_entidad='$cod_entidad_federal' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='1' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division'";
			$aux_cod_subdivision=$this->ccfd01_subdivision->field('cod_subdivision', $cond_subdivision, 'cod_subdivision DESC');
			$cod_subdivision=$aux_cod_subdivision + 1;
			$denominacion=$concepto_manejo.' - '.$cuenta_bancaria;
			$concepto='';

			$flag=1;
			// Se coloca el codigo de la dependencia 1 como constante, esto solo para el plan de cuentas contables. La cuenta bancaria queda normal (con su codigo de dependencia)
			$sql_consulta_subdivision="cod_presi='$cod_presi' AND cod_entidad='$cod_entidad_federal' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='1' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division' AND cod_subdivision='$cod_subdivision'";
			$count_subdiv=$this->ccfd01_subdivision->findCount($sql_consulta_subdivision);
			if($count_subdiv==0){// se verifica que no exista este registro en la tabla de divisiones.
				$sql_insert_subdivision="INSERT INTO ccfd01_subdivision VALUES ('$cod_presi', '$cod_entidad_federal', '$cod_tipo_inst', '$cod_inst', '1', '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$cod_subdivision', '$denominacion', '$concepto')";

				$result = $this->ccfd01_subdivision->execute($sql_insert_subdivision);
				$result > 0 ? $flag=1 : $flag=0;
			}

			$cond_consulta = $this->SQLCA()." and cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria'";
			$actualizacion_cuenta = "UPDATE cstd02_cuentas_bancarias SET tesoro_tipo='$cod_tipo_cuenta', tesoro_cuenta='$cod_cuenta', tesoro_subcuenta='$cod_subcuenta', tesoro_division='$cod_division', tesoro_subdivision='$cod_subdivision' WHERE ".$cond_consulta;
			if($this->cstd02_cuentas_bancarias->execute($actualizacion_cuenta)>0){
				if($flag==1){
					$this->cstd02_cuentas_bancarias->execute("COMMIT;");
					$this->set('Message_existe','Los datos fuer&oacute;n guardados exitosamente');
				}else{
					$this->cstd02_cuentas_bancarias->execute("ROLLBACK;");
					$this->set('errorMessage','La cuenta no fu&eacute; creada, no pudo ser registrada en el plan de cuentas');
					//$this->set('errorMessage','Los datos fuer&oacute;n guardados exitosamente, pero no fuer&oacute;n registrados en el plan de cuentas');
				}
			}else{
				if($flag==1){
					$this->cstd02_cuentas_bancarias->execute("ROLLBACK;");
					$this->set('errorMessage','La cuenta no fu&eacute; creada, no se pudo actualizar los valores del plan de cuenta');
					//$this->set('errorMessage','Los datos fuer&oacute;n guardados exitosamente, pero no fu&eacute;n actualizada la cuenta bancaria');
				}else{
					$this->cstd02_cuentas_bancarias->execute("ROLLBACK;");
					$this->set('errorMessage','Lo siento, no pudo ser creada la cuenta bancaria ni los asientos en el plan de cuentas');
					//$this->set('errorMessage','Lo siento, los datos fuer&oacute;n guardados, pero no pudo ser actualizado el plan de cuentas ni la cuenta bancaria');
				}
			}

			$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo_en');
			$busca=$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad;
			$this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'tipo_su');
			$this->set('read', 'readonly');
			$this->set('sel_en',$cod_entidad);
			$this->set('sel_su',$cod_sucursal);
			$this->in();
		}else{
			$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo_en');
			$busca=$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad;
			$this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'tipo_su');
			$this->set('errorMessage','Los datos no fuer&oacute;n guardados');
			$this->set('sel_en',$this->Session->read('s_ent'));
			$this->set('sel_su',$cod_sucursal);

		}// fin else insercion

	}// fin else consulta

	$this->data['cstp02_cuentas_bancarias']=array();
	$this->index();
	$this->render('index');

 	}//fin guardar

	function modificar($su=null,$en=null){
 	$this->layout ="ajax";
 	$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo');
	$busca=$this->SQLCA()." and cod_entidad_bancaria=".$en;
	$this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'tipo_su');
	$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$en));
	$this->set('datos_su', $this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$en.' and cod_sucursal='.$su));
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $en);
 	$this->set('sel_', $su);
 	}//fin modificar

	function modificar_consultar($su=null,$en=null){
 	$this->layout ="ajax";
 	$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo');
	$busca=$this->SQLCA()." and cod_entidad_bancaria=".$en;
	$this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'tipo_su');
	$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$en));
	$this->set('datos_su', $this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$en.' and cod_sucursal='.$su));
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $en);
 	$this->set('sel_', $su);

 	}//fin modificar

 	function guardar_modificar($entidad=null,$sucursal=null){
 	$this->layout ="ajax";

 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $entidad);
 	$cod_entidad = $this->data['cstp01_sucursales_bancarias']['codigo_entidad'];
 	$cod_sucursal = $this->data['cstp01_sucursales_bancarias']['codigo_sucursal'];
	$denominacion = $this->data['cstp01_sucursales_bancarias']['denominacion_sucursal'];
	$sql="update cstd01_sucursales_bancarias set denominacion='$denominacion' where cod_entidad_bancaria='$entidad' and cod_sucursal='$cod_sucursal'";
	if($this->cstd01_sucursales_bancarias->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron modificados exitosamente');
		$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo');
		$busca=$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad;
		$this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'tipo_su');
		$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$cod_entidad));
		$this->set('sel', $cod_entidad);
		$this->set('read', 'readonly');
		$this->in();
	}else{
		$this->set('errorMessage','Los datos no fueron modificados');
	}//fin else actualizacion

 	}//fin guardar modificar

 	function guardar_modificar_consultar($entidad=null,$sucursal=null){
 	$this->layout ="ajax";

 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $entidad);
 	$cod_entidad = $this->data['cstp01_sucursales_bancarias']['codigo_entidad'];
 	$cod_sucursal = $this->data['cstp01_sucursales_bancarias']['codigo_sucursal'];
	$denominacion = $this->data['cstp01_sucursales_bancarias']['denominacion_sucursal'];
	$sql="update cstd01_sucursales_bancarias set denominacion='$denominacion' where cod_entidad_bancaria='$entidad' and cod_sucursal='$cod_sucursal'";
	if($this->cstd01_sucursales_bancarias->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron modificados exitosamente');
		$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo');
		$busca=$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad;
		$this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'tipo_su');
		$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$cod_entidad));
		$this->set('sel', $cod_entidad);
		$this->set('read', 'readonly');
		$this->in();
	}else{
		$this->set('errorMessage','Los datos no fueron modificados');
	}//fin else actualizacion
	$this->consultar();

 	}//fin guardar modificar


 	function eliminar($su=null, $en=null){
 	$this->layout ="ajax";
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $en);
 	$cod_entidad = $this->data['cstp01_sucursales_bancarias']['codigo_entidad'];
 	$cod_sucursal = $this->data['cstp01_sucursales_bancarias']['codigo_sucursal'];
	$denominacion = $this->data['cstp01_sucursales_bancarias']['denominacion_sucursal'];
	$sql="delete from cstd01_sucursales_bancarias where cod_entidad_bancaria='$en' and cod_sucursal='$su'";
	if($this->cstd01_sucursales_bancarias->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron eliminados exitosamente');
		$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo');
		$busca=$this->SQLCA()." and cod_entidad_bancaria=".$en;
		$this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'tipo_su');
		$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$en));
		$this->set('sel', $en);
		$this->set('read', 'readonly');
		$this->in();
	}else{
		$this->set('errorMessage','Los datos no fueron eliminados');
		$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo');

	}//fin else eliminar


 	}//fin eliminar

	function eliminar_consultar($su=null, $en=null){
 	$this->layout ="ajax";
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $en);
 	$cod_entidad = $this->data['cstp01_sucursales_bancarias']['codigo_entidad'];
 	$cod_sucursal = $this->data['cstp01_sucursales_bancarias']['codigo_sucursal'];
	$denominacion = $this->data['cstp01_sucursales_bancarias']['denominacion_sucursal'];
	$sql="delete from cstd01_sucursales_bancarias where cod_entidad_bancaria='$en' and cod_sucursal='$su'";
	if($this->cstd01_sucursales_bancarias->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron eliminados exitosamente');
		$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo');
		$busca=$this->SQLCA()." and cod_entidad_bancaria=".$en;
		$this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'tipo_su');
		$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$en));
		$this->set('sel', $en);
		$this->set('read', 'readonly');
		$this->in();
	}else{
		$this->set('errorMessage','Los datos no fueron eliminados');
		$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo');

	}//fin else eliminar
$this->consultar();

 	}//fin eliminar





function eliminar_cuenta_bancaria($cod_entidad=null,$cod_sucursal=null,$cuenta_bancaria=null,$anterior=null){
	$this->layout ="ajax";

	$consulta=$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'";
	$datos_cuenta=$this->cstd02_cuentas_bancarias->findAll($consulta);
	if($this->cstd02_cuentas_bancarias->findCount($consulta)!=0){
		$deposito_ano     = $datos_cuenta[0]['cstd02_cuentas_bancarias']['deposito_ano'];
		$nota_credito_ano = $datos_cuenta[0]['cstd02_cuentas_bancarias']['nota_credito_ano'];
		$nota_debito_ano  = $datos_cuenta[0]['cstd02_cuentas_bancarias']['nota_debito_ano'];
		$cheque_ano       = $datos_cuenta[0]['cstd02_cuentas_bancarias']['cheque_ano'];

		// Si hay alguna cantidad distinta de cero (0) en los campos anuales, indica que hubo algun movimiento en la cuenta. (No puede ser eliminada)
		if($deposito_ano==0 && $nota_credito_ano==0 && $nota_debito_ano==0 && $cheque_ano==0){
			$tesoro_subdivision=$datos_cuenta[0]['cstd02_cuentas_bancarias']['tesoro_subdivision'];
			$cp = $this->Session->read('SScodpresi');
		    $ce = $this->Session->read('SScodentidad');
		    $cti = $this->Session->read('SScodtipoinst');
			$ci = $this->Session->read('SScodinst');
			$cd = $this->Session->read('SScoddep');
			$cod_tipo_cuenta=1;
			$cod_cuenta=102;
			$cod_subcuenta=002;
			$cod_division=$cod_entidad;
			$cod_subdivision=$tesoro_subdivision;
			// Se procede a eliminar la cuenta del plan de cuentas contables y luego de la tabla de cuentas bancarias.
			$sql_delete_subdivision="DELETE FROM ccfd01_subdivision WHERE cod_presi='$cp' AND cod_entidad='$ce' AND cod_tipo_inst='$cti' AND cod_inst='$ci' AND cod_dep='1' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division' AND cod_subdivision='$cod_subdivision'";
			if($this->ccfd01_division->execute($sql_delete_subdivision)>1){
				$eliminacion="DELETE FROM cstd02_cuentas_bancarias WHERE ".$consulta;
				if($this->cstd02_cuentas_bancarias->execute($eliminacion)>0){
					$this->set('Message_existe','La cuenta bancaria se elimino de manera correcta');
				}else{
					$this->set('errorMessage','Lo siento, la cuenta bancaria no pudo ser eliminada');
				}

			}else{
			$this->set('errorMessage','La cuenta bancaria no pudo ser eliminada, no se elimino del plan de cuentas');
			}

		}else{
		$this->set('errorMessage','Lo siento esa cuenta no puede ser eliminada, presenta un movimiento financiero');
		}

	}else{
		$this->set('errorMessage','Lo siento no pudo ser encontrada la cuenta bancaria');
	}

	$this->consultar($anterior);
	$this->render("consultar");
}//eliminar_cuenta_bancaria



function editar($cod_entidad=null,$cod_sucursal=null,$cuenta_bancaria=null,$anterior=null){
	$this->layout = "ajax";
	$consulta=$this->SQLCA()." and cod_entidad_bancaria=".$cod_entidad." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta_bancaria'";
	$datos_cuenta=$this->v_cuentas_bancarias->findAll($consulta);

	$this->set('datos',$datos_cuenta);
	$cadena_cuenta=$datos_cuenta[0]['v_cuentas_bancarias']['cuenta_bancaria'];
	$formato_cuenta=substr($cadena_cuenta, 0, 4)." ".substr($cadena_cuenta, 4, 4)." ".substr($cadena_cuenta, 8, 2)." ".substr($cadena_cuenta, 10, 10);
	$this->set('formato_cuenta',$formato_cuenta);
	$this->set('formato_cuenta',$formato_cuenta);
	$this->set('anterior',$anterior);
}



function guardar_modificacion($cod_entidad=null,$cod_sucursal=null,$cuenta_bancaria=null,$tipo_cuenta=null,$anterior=null){
	$this->layout="ajax";

	//pr($this->data);
	//echo "<br />".$cod_entidad;
	//echo "<br />".$cod_sucursal;
	//echo "<br />".$cuenta_bancaria;
	//echo "<br />".$tipo_cuenta;
	//echo "<br />".$radio_tipocuenta = $this->data['v_cuentas_bancarias']['radio_tipocuenta'];
	$radio_tipocuenta   = $this->data['v_cuentas_bancarias']['radio_tipocuenta'];
	$condicion_contabilidad = $this->data['v_cuentas_bancarias']['condicion_contabilidad'];
	$responsable_manejo = $this->data['v_cuentas_bancarias']['responsable_manejo'];
	$cargo_responsable  = $this->data['v_cuentas_bancarias']['cargo_responsable'];
	$concepto_manejo    = $this->data['v_cuentas_bancarias']['concepto_manejo'];

	$cp = $this->Session->read('SScodpresi');
    $ce = $this->Session->read('SScodentidad');
    $cti = $this->Session->read('SScodtipoinst');
	$ci = $this->Session->read('SScodinst');
	$cd = $this->Session->read('SScoddep');

	if($tipo_cuenta != $radio_tipocuenta){
		//echo "<br />"."se cambio el radio tipo cuenta, los dos son diferentes";
		$consulta = $this->SQLCA()." and cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria'";
		//$busqueda_cuenta = "UPDATE cstd02_cuentas_bancarias SET tesoro_tipo='$cod_tipo_cuenta', tesoro_cuenta='$cod_cuenta', tesoro_subcuenta='$cod_subcuenta', tesoro_division='$cod_division', tesoro_subdivision='$cod_subdivision' WHERE ".$cond_consulta;
		//$this->cstd02_cuentas_bancarias->findAll($cond_consulta, 'tesoro_tipo, tesoro_cuenta, tesoro_subcuenta, tesoro_division, tesoro_subdivision');

		if($this->cstd02_cuentas_bancarias->findCount($consulta)!=0){

			$datos_cuenta=$this->cstd02_cuentas_bancarias->findAll($consulta);
			$deposito_ano     = $datos_cuenta[0]['cstd02_cuentas_bancarias']['deposito_ano'];
			$nota_credito_ano = $datos_cuenta[0]['cstd02_cuentas_bancarias']['nota_credito_ano'];
			$nota_debito_ano  = $datos_cuenta[0]['cstd02_cuentas_bancarias']['nota_debito_ano'];
			$cheque_ano       = $datos_cuenta[0]['cstd02_cuentas_bancarias']['cheque_ano'];

			// Si hay alguna cantidad distinta de cero (0) en los campos anuales, indica que hubo algun movimiento en la cuenta. (No puede ser eliminada del plan de cuenta, pero la modificacion procede)
			if($deposito_ano==0 && $nota_credito_ano==0 && $nota_debito_ano==0 && $cheque_ano==0){
				$concepto_manejo=$datos_cuenta[0]['cstd02_cuentas_bancarias']['concepto_manejo'];
				$tipo_cuenta_tabla=$datos_cuenta[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];

				$aux_cod_tipo_cuenta = 1;
				$aux_cod_cuenta		 = $datos_cuenta[0]['cstd02_cuentas_bancarias']['tesoro_cuenta'];
				$aux_cod_subcuenta	 = $datos_cuenta[0]['cstd02_cuentas_bancarias']['tesoro_subcuenta'];
				$aux_cod_division	 = $datos_cuenta[0]['cstd02_cuentas_bancarias']['tesoro_division'];
				$aux_cod_subdivision = $datos_cuenta[0]['cstd02_cuentas_bancarias']['tesoro_subdivision'];

				if($radio_tipocuenta==1){
					$cod_tipo_cuenta=1;
					$cod_cuenta=102;
					$cod_subcuenta=002;
				}else{
					$cod_tipo_cuenta=1;
					$cod_cuenta=132;
					$cod_subcuenta=001;
				}

				$tesoro_subdivision=$datos_cuenta[0]['cstd02_cuentas_bancarias']['tesoro_subdivision'];
				$cod_division=$cod_entidad;
				$cod_subdivision_2=$tesoro_subdivision;
				// Se procede a eliminar la cuenta del plan de cuentas contables.
				$sql_delete_subdivision="DELETE FROM ccfd01_subdivision WHERE cod_presi='$cp' AND cod_entidad='$ce' AND cod_tipo_inst='$cti' AND cod_inst='$ci' AND cod_dep='1' AND cod_tipo_cuenta='$aux_cod_tipo_cuenta' AND cod_cuenta='$aux_cod_cuenta' AND cod_subcuenta='$aux_cod_subcuenta' AND cod_division='$aux_cod_division' AND cod_subdivision='$aux_cod_subdivision'";
				if($this->ccfd01_division->execute($sql_delete_subdivision)>1){

					$cond_subdivision="cod_presi='$cp' AND cod_entidad='$ce' AND cod_tipo_inst='$cti' AND cod_inst='$ci' AND cod_dep='$cd' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division'";
					$aux_cod_subdivision = $this->ccfd01_subdivision->field('cod_subdivision', $cond_subdivision, 'cod_subdivision DESC');
					$cod_subdivision=$aux_cod_subdivision + 1;
					$denominacion=$cuenta_bancaria.' - '.$concepto_manejo;
					$concepto='';

					$flag=1;
					$sql_consulta_subdivision="cod_presi='$cp' AND cod_entidad='$ce' AND cod_tipo_inst='$cti' AND cod_inst='$ci' AND cod_dep='1' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division' AND cod_subdivision='$cod_subdivision'";
					$count_subdiv=$this->ccfd01_subdivision->findCount($sql_consulta_subdivision);
					if($count_subdiv==0){// Se verifica que no exista este registro en la tabla de divisiones.
						$sql_insert_subdivision="INSERT INTO ccfd01_subdivision VALUES ('$cp', '$ce', '$cti', '$ci', '1', '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$cod_subdivision', '$denominacion', '$concepto')";
						$result = $this->ccfd01_subdivision->execute($sql_insert_subdivision);
						$result > 0 ? $flag=1 : $flag=0;
						//echo "paso por aqui 11";
					}

					if($flag==1){
						$actualizacion_cuenta = "UPDATE cstd02_cuentas_bancarias SET concepto_manejo='$concepto_manejo', responsable_manejo='$responsable_manejo', cargo_responsable='$cargo_responsable', tipo_cuenta='$radio_tipocuenta', tesoro_tipo='$cod_tipo_cuenta', tesoro_cuenta='$cod_cuenta', tesoro_subcuenta='$cod_subcuenta', tesoro_division='$cod_division', tesoro_subdivision='$cod_subdivision', condicion_contabilidad='$condicion_contabilidad' WHERE ".$consulta;
						if($this->cstd02_cuentas_bancarias->execute($actualizacion_cuenta)>0){
							if($flag==1){
							$this->set('Message_existe','Los datos fuer&oacute;n modificados exitosamente');
							}else{
							$this->set('errorMessage','Los datos fuer&oacute;n modificados exitosamente, pero no fuer&oacute;n registrados en el plan de cuentas');
							}
						}else{
							if($flag==1){
							$this->set('errorMessage','Los datos fuer&oacute;n modificados exitosamente, pero no fu&eacute;n actualizada la cuenta bancaria');
							}else{
							$this->set('errorMessage','Lo siento, los datos fuer&oacute;n modificados, pero no pudo ser actualizado el plan de cuentas ni la cuenta bancaria');
							}
						}
					}else{
						$this->set('errorMessage','No fue creado el registro el plan de cuentas ni fue modificada la cuenta bancaria');
					}

				}else{
					$this->set('errorMessage','No pudo ser elimininado el registro del plan de cuentas contables');
				}


			}else{ // No se elimina la cuenta del plan de cuentas contables, ya que presente al menos un movimiento, pero si se crea la nueva cuenta y se actualiza la tabla: cuentas bancarias.


				$datos_cuenta=$this->cstd02_cuentas_bancarias->findAll($consulta);
				$concepto_manejo=$datos_cuenta[0]['cstd02_cuentas_bancarias']['concepto_manejo'];
				$tipo_cuenta_tabla=$datos_cuenta[0]['cstd02_cuentas_bancarias']['tipo_cuenta'];

				$aux_cod_tipo_cuenta = 1;
				$aux_cod_cuenta		 = $datos_cuenta[0]['cstd02_cuentas_bancarias']['tesoro_cuenta'];
				$aux_cod_subcuenta	 = $datos_cuenta[0]['cstd02_cuentas_bancarias']['tesoro_subcuenta'];
				$aux_cod_division	 = $datos_cuenta[0]['cstd02_cuentas_bancarias']['tesoro_division'];
				$aux_cod_subdivision = $datos_cuenta[0]['cstd02_cuentas_bancarias']['tesoro_subdivision'];

				if($radio_tipocuenta==1){
					$cod_tipo_cuenta=1;
					$cod_cuenta=102;
					$cod_subcuenta=002;
				}else{
					$cod_tipo_cuenta=1;
					$cod_cuenta=132;
					$cod_subcuenta=001;
				}

				$tesoro_subdivision=$datos_cuenta[0]['cstd02_cuentas_bancarias']['tesoro_subdivision'];
				$cod_division=$cod_entidad;

				$cond_subdivision="cod_presi='$cp' AND cod_entidad='$ce' AND cod_tipo_inst='$cti' AND cod_inst='$ci' AND cod_dep='$cd' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division'";
				$aux_cod_subdivision = $this->ccfd01_subdivision->field('cod_subdivision', $cond_subdivision, 'cod_subdivision DESC');
				$cod_subdivision=$aux_cod_subdivision + 1;
				$denominacion=$cuenta_bancaria.' - '.$concepto_manejo;
				$concepto='';

				$flag=1;
				$sql_consulta_subdivision="cod_presi='$cp' AND cod_entidad='$ce' AND cod_tipo_inst='$cti' AND cod_inst='$ci' AND cod_dep='1' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division' AND cod_subdivision='$cod_subdivision'";
				$count_subdiv=$this->ccfd01_subdivision->findCount($sql_consulta_subdivision);
				if($count_subdiv==0){// Se verifica que no exista este registro en la tabla de divisiones.
					$sql_insert_subdivision="INSERT INTO ccfd01_subdivision VALUES ('$cp', '$ce', '$cti', '$ci', '1', '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$cod_subdivision', '$denominacion', '$concepto')";
					$result = $this->ccfd01_subdivision->execute($sql_insert_subdivision);
					echo "paso por aqui 2";
					$result > 0 ? $flag=1 : $flag=0;
				}

				if($flag==1){
					$actualizacion_cuenta = "UPDATE cstd02_cuentas_bancarias SET concepto_manejo='$concepto_manejo', responsable_manejo='$responsable_manejo', cargo_responsable='$cargo_responsable', tipo_cuenta='$radio_tipocuenta', tesoro_tipo='$cod_tipo_cuenta', tesoro_cuenta='$cod_cuenta', tesoro_subcuenta='$cod_subcuenta', tesoro_division='$cod_division', tesoro_subdivision='$cod_subdivision', condicion_contabilidad='$condicion_contabilidad' WHERE ".$consulta;
					if($this->cstd02_cuentas_bancarias->execute($actualizacion_cuenta)>0){
						if($flag==1){
						$this->set('Message_existe','Los datos fuer&oacute;n guardados exitosamente');
						}else{
						$this->set('errorMessage','Los datos fuer&oacute;n guardados exitosamente, pero no fuer&oacute;n registrados en el plan de cuentas');
						}
					}else{
						if($flag==1){
						$this->set('errorMessage','Los datos fuer&oacute;n guardados exitosamente, pero no fu&eacute;n actualizada la cuenta bancaria');
						}else{
						$this->set('errorMessage','Lo siento, los datos fuer&oacute;n guardados, pero no pudo ser actualizado el plan de cuentas ni la cuenta bancaria');
						}
					}

				}else{
					$this->set('errorMessage','No fue creado el registro el plan de cuentas ni fue modificada la cuenta bancaria');
				}

			}


		}else{
			$this->set('errorMessage','Disculpe, La cuenta bancaria no pudo ser encontrada');
		}


	}else{
		//echo "<br />"."son iguales no se cambio el radio tipo cuenta";
		$responsable_manejo = $this->data['v_cuentas_bancarias']['responsable_manejo'];
		$cargo_responsable  = $this->data['v_cuentas_bancarias']['cargo_responsable'];
		$cond_consulta = $this->SQLCA()." and cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria'";
		$actualizacion_cuenta = "UPDATE cstd02_cuentas_bancarias SET concepto_manejo='$concepto_manejo', responsable_manejo='$responsable_manejo', cargo_responsable='$cargo_responsable', condicion_contabilidad='$condicion_contabilidad' WHERE ".$cond_consulta;
		if($this->cstd02_cuentas_bancarias->execute($actualizacion_cuenta)>0){
			$this->set('Message_existe','Los datos fuer&oacute;n modificados exitosamente');
		}else{
			$this->set('errorMessage','Los datos fuer&oacute;n modificados exitosamente');
		}
		//echo "paso solo por aqui";
	}

	$anterior=$anterior+1;
	$this->consultar($anterior);
	$this->render("consultar");
}



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


function consultar($pagina=null){
	$this->layout = "ajax";
		if($pagina!=null){
			$pagina=$pagina;
			$Tfilas=$this->v_cuentas_bancarias->findCount($this->SQLCA());
			if($Tfilas==0){
				$this->index();
				$this->render("index");
			}
			if($Tfilas!=0){
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$datacpcp01=$this->v_cuentas_bancarias->findAll($this->SQLCA(),null,'cod_entidad_bancaria, cod_sucursal, cuenta_bancaria ASC',1,$pagina,null);
			$this->set('datos',$datacpcp01);
			$this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
			$cadena_cuenta=$datacpcp01[0]['v_cuentas_bancarias']['cuenta_bancaria'];
			$formato_cuenta=substr($cadena_cuenta, 0, 4)." ".substr($cadena_cuenta, 4, 4)." ".substr($cadena_cuenta, 8, 2)." ".substr($cadena_cuenta, 10, 10);
			$this->set('formato_cuenta',$formato_cuenta);

			/*
			 * Para habilitar y deshabilitar el boton de eliminar en la consulta
			 * */
			if($datacpcp01[0]['v_cuentas_bancarias']['deposito_ano']==0 && $datacpcp01[0]['v_cuentas_bancarias']['nota_credito_ano']==0 && $datacpcp01[0]['v_cuentas_bancarias']['nota_debito_ano']==0 && $datacpcp01[0]['v_cuentas_bancarias']['cheque_ano']==0){
			   $b_eliminacion="enabled";
			   $this->set('b_eliminacion',$b_eliminacion);
			}else{
			   $b_eliminacion="disabled";
			   $this->set('b_eliminacion',$b_eliminacion);
			}

		}
 }else{
 	$pagina=1;
          	 $Tfilas=$this->v_cuentas_bancarias->findCount($this->SQLCA());
          	 // echo $Tfilas;
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_cuentas_bancarias->findAll($this->SQLCA(),null,'cod_entidad_bancaria, cod_sucursal, cuenta_bancaria ASC',1,$pagina,null);
          	 $this->set('datos',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);

			$cadena_cuenta=$datacpcp01[0]['v_cuentas_bancarias']['cuenta_bancaria'];
			$formato_cuenta=substr($cadena_cuenta, 0, 4)." ".substr($cadena_cuenta, 4, 4)." ".substr($cadena_cuenta, 8, 2)." ".substr($cadena_cuenta, 10, 10);
			$this->set('formato_cuenta',$formato_cuenta);

				/*
				 * Para habilitar y deshabilitar el boton de eliminar en la consulta
				 * */
				if($datacpcp01[0]['v_cuentas_bancarias']['deposito_ano']==0 && $datacpcp01[0]['v_cuentas_bancarias']['nota_credito_ano']==0 && $datacpcp01[0]['v_cuentas_bancarias']['nota_debito_ano']==0 && $datacpcp01[0]['v_cuentas_bancarias']['cheque_ano']==0){
				   $b_eliminacion="enabled";
				   $this->set('b_eliminacion',$b_eliminacion);
				}else{
				   $b_eliminacion="disabled";
				   $this->set('b_eliminacion',$b_eliminacion);
				}
}
         }
}//fin function consultar2




function consultar2($entidad=null,$sucursal=null,$cuenta=null,$pagina=null){
 		$this->layout = "ajax";

		$entidad;
		$sucursal;
		$cuenta;
		$pagina;
 		//$condicion=$this->SQLCA()." and cod_entidad_bancaria='$entidad' and cod_sucursal='$sucursal' and cuenta_bancaria='$cuenta'";
 		$condicion=$this->SQLCA()." and cuenta_bancaria='$cuenta'";
 		$buscar="SELECT * FROM v_cuentas_bancarias WHERE ".$condicion;
        //$datos=$this->v_cuentas_bancarias->execute($buscar);
        //$this->set('datos',$datos);

		$pagina=1;
        $Tfilas=$this->v_cuentas_bancarias->findCount($this->SQLCA());
        $datacpcp01=$this->v_cuentas_bancarias->findAll($condicion,null,'cod_entidad_bancaria ASC',1,$pagina,null);
        $this->set('datos',$datacpcp01);
        $this->set('siguiente',$pagina+1);
        $this->set('anterior',$pagina-1);
        $this->bt_nav($Tfilas,$pagina);
        $cadena_cuenta=$datacpcp01[0]['v_cuentas_bancarias']['cuenta_bancaria'];
		$formato_cuenta=substr($cadena_cuenta, 0, 4)." ".substr($cadena_cuenta, 4, 4)." ".substr($cadena_cuenta, 8, 2)." ".substr($cadena_cuenta, 10, 10);
		$this->set('formato_cuenta',$formato_cuenta);

		/*
		 * Para habilitar y deshabilitar el boton de eliminar en la consulta
		 * */
		if($datacpcp01[0]['v_cuentas_bancarias']['deposito_ano']==0 && $datacpcp01[0]['v_cuentas_bancarias']['nota_credito_ano']==0 && $datacpcp01[0]['v_cuentas_bancarias']['nota_debito_ano']==0 && $datacpcp01[0]['v_cuentas_bancarias']['cheque_ano']==0){
		   $b_eliminacion="enabled";
		   $this->set('b_eliminacion',$b_eliminacion);
		}else{
		   $b_eliminacion="disabled";
		   $this->set('b_eliminacion',$b_eliminacion);
		}
}//fin function consultar2



 function salir(){
 	$this->layout ="ajax";
 	$this->set('tipo_en',array());
 	$this->set('tipo_su',array());
 	$this->Session->delete('s_ent');
 	$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo_en');
	$this->set('enable', 'disabled');
 	}


 //Otras Funciones validas de aqui para abajo.
 function prebusqueda(){
 	$this->layout ="ajax";
    $this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'entidades');
 }



 function mostrar1($cod_ent=null){
	$this->layout = "ajax";

	//echo "<br>Hola Mostrar 1";
	//echo "<br>Entidad: ".$cod_ent;

	if($cod_ent!=null){
	 	$entidades=$this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$cod_ent);
		$cod_entidad = $this->mascara3($entidades[0]['cstd01_entidades_bancarias']['cod_entidad_bancaria']);
		$deno_entidad = $entidades[0]['cstd01_entidades_bancarias']['denominacion'];
		$this->set('cod_entidad',$cod_entidad);
		$this->set('deno_entidad',$deno_entidad);
	}else{
		$this->set('cod_entidad','');
		$this->set('deno_entidad','');
		$this->set('mensajeError','NO HA SELECCIONADO ALGUNA ENTIDAD BANCARIA');
	}
 }


 function select_sucursales($cod_ent=null){
	$this->layout = "ajax";

	//echo "<br>Hola Select Sucursales";
	//echo "<br>Entidad: ".$cod_ent;

	if($cod_ent!=null){
		    $busca=$this->SQLCA()." and cod_entidad_bancaria=".$cod_ent;
			$this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'sucursales');
				   $this->set('cod_entidad',$cod_ent);
	}else{
	   $this->set('sucursales','');
 	   $this->set('cod_entidad','');
	}
}



function mostrar2($cod_ent=null,$cod_sucursal=null){
	$this->layout = "ajax";
	//echo "<br>Hola Mostrar 2";
	//echo "<br>Entidad: ".$cod_ent;
	//echo "<br>Sucursal: ".$cod_sucursal;

		if($cod_sucursal!=null){
		 	$sucursales=$this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$cod_ent." and cod_sucursal=".$cod_sucursal);
			$cod_sucursal = $this->mascara3($sucursales[0]['cstd01_sucursales_bancarias']['cod_sucursal']);
			$deno_sucursal = $sucursales[0]['cstd01_sucursales_bancarias']['denominacion'];
			$this->set('cod_entidad_bancaria',$cod_ent);
			$this->set('cod_sucursal',$cod_sucursal);
			$this->set('deno_sucursal',$deno_sucursal);
		}else{
			$this->set('cod_entidad_bancaria',$cod_ent);
			$this->set('cod_sucursal','');
			$this->set('deno_sucursal','');
		}

}



function select_cuentas($cod_ent=null,$cod_sucursal=null){
	$this->layout="ajax";

	if($cod_sucursal==''){
	$this->set('cuentas',array());//Se manda un array vacio porque no selecciono una sucursal bancaria. Marco el opcion blanco (vacio)
	$this->set('existe','no');
	$this->set('cod_entidad',$cod_ent);
	$this->set('cod_sucursal',$cod_sucursal);
	}else{
			//echo "<br>Hola Select Cuentas";
			//echo "<br>Entidad: ".$cod_ent;
			//echo "<br>Sucursal: ".$cod_sucursal;
			$cond=$this->SQLCA()." and cod_entidad_bancaria=".$cod_ent." and cod_sucursal=".$cod_sucursal;
			$cuentas = $this->v_cuentas_bancarias->generateList($cond, 'cod_entidad_bancaria, cuenta_bancaria ASC', null, '{n}.v_cuentas_bancarias.cuenta_bancaria', '{n}.v_cuentas_bancarias.concepto_manejo');
			if($cuentas!=null){
				$this->concatena($cuentas, 'cuentas');
				$this->set('existe','si');
				$this->set('cod_entidad',$cod_ent);
				$this->set('cod_sucursal',$cod_sucursal);
			}else{
				$this->set('cuentas',array('no'=>'no existen cuentas'));
				$this->set('existe','no');
				$this->set('cod_entidad',$cod_ent);
				$this->set('cod_sucursal',$cod_sucursal);
			}
	}
}


 function mostrar3($cod_ent=null,$cod_sucursal=null,$cuenta=null){
 	$this->layout="ajax";

 	if($cuenta==''){//La cuenta viene vacia.
 		$this->set('concepto_manejo','');
 	}else{
		 	if($cod_ent!=null && $cod_sucursal!=null){
		 		if($cuenta=='no'){
		 			$this->set('concepto_manejo','');
		 		}else{
				 	//echo "<br>".$cod_ent;
				 	//echo "<br>".$cod_sucursal;
				 	//echo "<br>".$cuenta;
				 	$cond=$this->SQLCA()." and cod_entidad_bancaria=".$cod_ent." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta'";
				 	$datacpcp01=$this->v_cuentas_bancarias->findAll($cond,array('concepto_manejo'));
				 	$this->set('concepto_manejo',$datacpcp01[0]['v_cuentas_bancarias']['concepto_manejo']);
		 		}
		 	}else{
		 		//erro no llegaron algunos datos
		 	}
	}
 }



 function mostrar_datos_cuentabancaria($cod_ent=null,$cod_sucursal=null,$cuenta=null){
 	$this->layout="ajax";
 	//echo "<br>".$cod_ent;
 	//echo "<br>".$cod_sucursal;
 	//echo "<br>".$cuenta;

 	if($cuenta=='no' || $cuenta==''){
			$this->set('datos','vacio');
			$this->set('errorMessage','NO SELECCIONO LA CUENTA BANCARIA');
 	}else{
		 	$cond=$this->SQLCA()." and cod_entidad_bancaria=".$cod_ent." and cod_sucursal=".$cod_sucursal." and cuenta_bancaria='$cuenta'";
		 	$datacpcp01=$this->v_cuentas_bancarias->findAll($cond);
		 	$this->set('datos',$datacpcp01);

		 	$cadena_cuenta=$datacpcp01[0]['v_cuentas_bancarias']['cuenta_bancaria'];
			$formato_cuenta=substr($cadena_cuenta, 0, 4)." ".substr($cadena_cuenta, 4, 4)." ".substr($cadena_cuenta, 8, 2)." ".substr($cadena_cuenta, 10, 10);
			$this->set('formato_cuenta',$formato_cuenta);

			/*
			 * Para habilitar y deshabilitar el boton de eliminar en la consulta
			 * */
			if($datacpcp01[0]['v_cuentas_bancarias']['deposito_ano']==0 && $datacpcp01[0]['v_cuentas_bancarias']['nota_credito_ano']==0 && $datacpcp01[0]['v_cuentas_bancarias']['nota_debito_ano']==0 && $datacpcp01[0]['v_cuentas_bancarias']['cheque_ano']==0){
			   $b_eliminacion="enabled";
			   $this->set('b_eliminacion',$b_eliminacion);
			 }else{
			   $b_eliminacion="disabled";
			   $this->set('b_eliminacion',$b_eliminacion);
			 }
 	}

 }//Fin mostrar_datos_cuentabancaria


 function vacio(){$this->layout="ajax";}


 function script_actualizar_cuentas_bancarias_plandecuentas(){
 	$this->layout="ajax";

 	$cp = $this->Session->read('SScodpresi');
	$ce = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci = $this->Session->read('SScodinst');
    $cd = $this->Session->read('SScoddep');

	$condicion_dep = "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci'";
	$distinct_entidades = $this->cstd02_cuentas_bancarias->execute("SELECT cod_dep, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, concepto_manejo, tipo_cuenta from cstd02_cuentas_bancarias WHERE ".$condicion_dep." ORDER BY cod_entidad_bancaria, cuenta_bancaria");

	for($i=0; $i<count($distinct_entidades); $i++){
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad_2 = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');

		$cod_dep         = $distinct_entidades[$i][0]['cod_dep'];
		$cod_entidad     = $distinct_entidades[$i][0]['cod_entidad_bancaria'];
		$cod_sucursal    = $distinct_entidades[$i][0]['cod_sucursal'];
		$cuenta_bancaria = $distinct_entidades[$i][0]['cuenta_bancaria'];
		$concepto_manejo = $distinct_entidades[$i][0]['concepto_manejo'];
		$tipo_cuenta	 = $distinct_entidades[$i][0]['tipo_cuenta'];


		if($tipo_cuenta == 1){
			$cod_tipo_cuenta = 1;
			$cod_cuenta      = 102;
			$cod_subcuenta   = 002;
			$cod_division    = $distinct_entidades[$i][0]['cod_entidad_bancaria'];
			$cod_subdivision = '';
			$denominacion=$concepto_manejo.' - '.$cuenta_bancaria;
			$concepto='';

		}elseif($tipo_cuenta == 2){
			$cod_tipo_cuenta = 1;
			$cod_cuenta      = 132;
			$cod_subcuenta   = 001;
			$cod_division    = $distinct_entidades[$i][0]['cod_entidad_bancaria'];
			$cod_subdivision = '';
			$denominacion=$concepto_manejo.' - '.$cuenta_bancaria;
			$concepto='';

			$sql_consulta_division="cod_presi='$cod_presi' AND cod_entidad='$cod_entidad_2' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='1' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division' AND ".$i."=".$i;
			if($this->ccfd01_division->findCount($sql_consulta_division)==0){// Se verifica que no exista este registro en la tabla de divisiones.
				$ent=$this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$distinct_entidades[$i][0]['cod_entidad_bancaria'],'denominacion','cod_entidad_bancaria ASC');
				$deno_entidad = $ent[0]['cstd01_entidades_bancarias']['denominacion'];

				$sql_insert_division="INSERT INTO ccfd01_division VALUES ('$cod_presi', '$cod_entidad_2', '$cod_tipo_inst', '$cod_inst', '1', '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$deno_entidad', '$concepto')";
				$result = $this->ccfd01_division->execute($sql_insert_division);
				echo "<br />La entidad: ".$cod_division."-".$denominacion." registrada correctamente";
			}else{
				echo "<br />La entidad: ".$cod_division."-".$denominacion." ya se encuentran en el plan de cuentas";
			}
		}

		//$cod_tipo_cuenta = 1;
		//$cod_cuenta      = 102;
		//$cod_subcuenta   = 002;
		//$cod_division    = $distinct_entidades[$i][0]['cod_entidad_bancaria'];
		//$cod_subdivision = '';
		//$denominacion=$cuenta_bancaria.' - '.$concepto_manejo;
		//$concepto='';

		$cond_subdivision="cod_presi='$cp' AND cod_entidad='$ce' AND cod_tipo_inst='$cti' AND cod_inst='$ci' AND cod_dep=1 AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division' AND ".$i."=".$i;
		$aux_cod_subdivision  = $this->ccfd01_subdivision->field('cod_subdivision', $cond_subdivision, 'cod_subdivision DESC');
		$cod_subdivision = $aux_cod_subdivision+1;

		$flag=1;
		//se coloca el codigo de la dependencia 1 como constante, esto solo para el plan de cuentas contables. La cuenta bancaria queda normal (con su codigo de dependencia)
		$sql_consulta_subdivision="cod_presi='$cp' AND cod_entidad='$ce' AND cod_tipo_inst='$cti' AND cod_inst='$ci' AND cod_dep='$cd' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division' AND cod_subdivision='$cod_subdivision' AND ".$i."=".$i;
		$count_subdiv=$this->ccfd01_subdivision->findCount($sql_consulta_subdivision);
		if($count_subdiv==0){// se verifica que no exista este registro en la tabla de divisiones.
			echo "<br />Entro   ";
				$sql_insert_subdivision="INSERT INTO ccfd01_subdivision VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$cod_subdivision', '$denominacion', '$concepto')";
				$result = $this->ccfd01_subdivision->execute($sql_insert_subdivision);
				$result > 0 ? $flag=1 : $flag=0;
		}else{
			echo "<br />No entro   ";
		}


		$cond_consulta = $condicion_dep." and cod_dep='$cod_dep' and cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' AND ".$i."=".$i;
		$actualizacion_cuenta = "UPDATE cstd02_cuentas_bancarias SET tesoro_tipo='$cod_tipo_cuenta', tesoro_cuenta='$cod_cuenta', tesoro_subcuenta='$cod_subcuenta', tesoro_division='$cod_division', tesoro_subdivision='$cod_subdivision' WHERE ".$cond_consulta;
		if($this->cstd02_cuentas_bancarias->execute($actualizacion_cuenta)>0){
			if($flag==1){
				echo " - Los datos fuer&oacute;n guardados exitosamente";
			}else{
				echo "<br /> - Los datos fuer&oacute;n guardados exitosamente, pero no fuer&oacute;n registrados en el plan de cuentas";
			}
		}else{
			if($flag==1){
				echo "<br /> - Los datos fuer&oacute;n guardados exitosamente, pero no fue actualizada la cuenta bancaria";
			}else{
				echo "<br /> - Lo siento, los datos fuer&oacute;n guardados, pero no pudo ser actualizado el plan de cuentas ni la cuenta bancaria";
			}
		}

	}





	//$consulta="select cod_dep, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, concepto_manejo from cstd02_cuentas_bancarias where ".$condicion_dep." ORDER BY cod_entidad_bancaria, cod_sucursal, cuenta_bancaria";
 	//$consulta="select * from cstd02_cuentas_bancarias where ".$this->SQLCA()." and cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria'";
 	//$datos_entidades = $this->cstd02_cuentas_bancarias->execute($consulta);
 	//pr($datos_entidades);

 	//echo "Existen: ".count($datos_entidades)." cuentas bancarias en la institucion.<br>";


	/*
 	for($i=0; $i<count($datos_entidades); $i++){
 		$cod_dep         = $datos_entidades[$i][0]['cod_dep'];
 		$cod_entidad     = $datos_entidades[$i][0]['cod_entidad_bancaria'];
 		$cod_sucursal    = $datos_entidades[$i][0]['cod_sucursal'];
 		$cuenta_bancaria = $datos_entidades[$i][0]['cuenta_bancaria'];
 		$concepto_manejo = $datos_entidades[$i][0]['concepto_manejo'];

 		$cod_tipo_cuenta = 1;
		$cod_cuenta      = 102;
		$cod_subcuenta   = 002;
		$cod_division    = $datos_entidades[$i][0]['cod_entidad_bancaria'];

		echo "<br />".$cond_subdivision="cod_presi='$cp' AND cod_entidad='$ce' AND cod_tipo_inst='$cti' AND cod_inst='$ci' AND cod_dep=1 AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division'";
		//$sql_maximo = "SELECT max(cod_subdivision) as cod_subdivision FROM ccfd01_subdivision WHERE ".$cond_subdivision;
		//$maximo = $this->ccfd01_subdivision->findCount($cond_subdivision);
		$aux_cod_subdivision = $this->ccfd01_subdivision->field('cod_subdivision', $cond_subdivision, 'cod_subdivision DESC');
		echo "<br />Aux_cod_subdivision: ".$aux_cod_subdivision;
		echo "<br />Cod_subdivision: ".$cod_subdivision = $aux_cod_subdivision + 1;
		$denominacion=$cuenta_bancaria.' - '.$concepto_manejo;
		$concepto='';

		//echo " - ".$cod_entidad." - ".$cod_sucursal." - ".$cuenta_bancaria." - ".$aux_cod_subdivision." - ".$cod_subdivision."<br />";

		$flag=1;
		$sql_consulta_subdivision="cod_presi='$cp' AND cod_entidad='$ce' AND cod_tipo_inst='$cti' AND cod_inst='$ci' AND cod_dep='1' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division' AND cod_subdivision='$cod_subdivision'";//se coloca el codigo de la dependencia 1 como constante, esto solo para el plan de cuentas contables. La cuenta bancaria queda normal (con su codigo de dependencia)
		$count_subdiv=$this->ccfd01_subdivision->findCount($sql_consulta_subdivision);
		if($count_subdiv==0){// se verifica que no exista este registro en la tabla de divisiones.
		echo "<br />Entro:   ";
			echo "<br />".$sql_insert_subdivision="INSERT INTO ccfd01_subdivision VALUES ('$cp', '$ce', '$cti', '$ci', 1, '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$cod_subdivision', '$denominacion', '$concepto')";
			$result = $this->ccfd01_subdivision->execute($sql_insert_subdivision);
			$result > 0 ? $flag=1 : $flag=0;
		}else{
			echo "<br />No entro:   ";
		}


		$cond_consulta = $condicion_dep." and cod_dep='$cod_dep' and cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria'";
		$actualizacion_cuenta = "UPDATE cstd02_cuentas_bancarias SET tesoro_tipo='$cod_tipo_cuenta', tesoro_cuenta='$cod_cuenta', tesoro_subcuenta='$cod_subcuenta', tesoro_division='$cod_division', tesoro_subdivision='$cod_subdivision' WHERE ".$cond_consulta;
		if($this->cstd02_cuentas_bancarias->execute($actualizacion_cuenta)>0){
			if($flag==1){
				echo "<br /> - Los datos fuer&oacute;n guardados exitosamente";
			}else{
				echo "<br /> - Los datos fuer&oacute;n guardados exitosamente, pero no fuer&oacute;n registrados en el plan de cuentas";
			}
		}else{
			if($flag==1){
				echo "<br /> - Los datos fuer&oacute;n guardados exitosamente, pero no fue actualizada la cuenta bancaria";
			}else{
				echo "<br /> - Lo siento, los datos fuer&oacute;n guardados, pero no pudo ser actualizado el plan de cuentas ni la cuenta bancaria";
			}
		}
 	}


	//----------------------------------------------------------------------------------------------------------------------------------------
	/*
 	if($this->cstd02_cuentas_bancarias->execute($consulta)>1){
		$cod_tipo_cuenta=1;
		$cod_cuenta=102;
		$cod_subcuenta=002;
		$cod_division=$cod_entidad;// codigo de la entidad bancaria.
		$cond_subdivision="cod_presi='$cod_presi' AND cod_entidad='$cod_entidad_federal' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division'";
		//$aux_cod_subdivision=$this->ccfd01_subdivision->findAll($cond_subdivision);
		$aux_cod_subdivision = $this->ccfd01_subdivision->field('cod_subdivision', $cond_subdivision, 'cod_subdivision DESC');
		echo "<br>codigo subdivision: ".$aux_cod_subdivision;
		echo "<br>codigo subdivision + 1: ".$cod_subdivision=$aux_cod_subdivision + 1;
		$denominacion=$cuenta_bancaria.' - '.$concepto_manejo;
		$concepto='';

		$flag=1;
		//*** se coloca el codigo de la dependencia 1 como constante, esto solo para el plan de cuentas contables. La cuenta bancaria queda normal (con su codigo de dependencia)
		$sql_consulta_subdivision="cod_presi='$cod_presi' AND cod_entidad='$cod_entidad_federal' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='1' AND cod_tipo_cuenta='$cod_tipo_cuenta' AND cod_cuenta='$cod_cuenta' AND cod_subcuenta='$cod_subcuenta' AND cod_division='$cod_division' AND cod_subdivision='$cod_subdivision'";
		echo "<br>count ".$count_subdiv=$this->ccfd01_subdivision->findCount($sql_consulta_subdivision);
		if($count_subdiv==0){// se verifica que no exista este registro en la tabla de divisiones.
		echo "<br>entro";
			$sql_insert_subdivision="INSERT INTO ccfd01_subdivision VALUES ('$cod_presi', '$cod_entidad_federal', '$cod_tipo_inst', '$cod_inst', '1', '$cod_tipo_cuenta', '$cod_cuenta', '$cod_subcuenta', '$cod_division', '$cod_subdivision', '$denominacion', '$concepto')";
			$result = $this->ccfd01_subdivision->execute($sql_insert_subdivision);
			$result > 0 ? $flag=1 : $flag=0;
		}
		//***

		$cond_consulta = $this->SQLCA()." and cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria'";
		$actualizacion_cuenta = "UPDATE cstd02_cuentas_bancarias SET tesoro_tipo='$cod_tipo_cuenta', tesoro_cuenta='$cod_cuenta', tesoro_subcuenta='$cod_subcuenta', tesoro_division='$cod_division', tesoro_subdivision='$cod_subdivision' WHERE ".$cond_consulta;
		if($this->cstd02_cuentas_bancarias->execute($actualizacion_cuenta)>0){
			if($flag==1){
				$this->set('Message_existe','Los datos fuer&oacute;n guardados exitosamente');
			}else{
				$this->set('errorMessage','Los datos fuer&oacute;n guardados exitosamente, pero no fuer&oacute;n registrados en el plan de cuentas');
			}
		}else{
			if($flag==1){
				$this->set('errorMessage','Los datos fuer&oacute;n guardados exitosamente, pero no fue actualizada la cuenta bancaria');
			}else{
				$this->set('errorMessage','Lo siento, los datos fuer&oacute;n guardados, pero no pudo ser actualizado el plan de cuentas ni la cuenta bancaria');
			}
		}
	}
	*/

 	echo "<br /><br />Si llamo bien";
 }


 }//Fin class
?>
