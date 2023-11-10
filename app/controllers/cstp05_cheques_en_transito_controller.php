<?php
/*
 * Creado el  08/12/2007 a las 09:14:46 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
class Cstp05ChequesEnTransitoController extends AppController {
 	var $name = 'cstp05_cheques_en_transito';
 	var $uses = array ('v_cstd01_bancos','v_cstd01_sucursales','cstd01_entidades_bancarias', 'cstd01_sucursales_bancarias', 'cstd02_cuentas_bancarias', 'ccfd04_cierre_mes',
                       'cstd03_cheque_cuerpo', 'cstd05_cheques_transito', 'v_vistas_cheques_union');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');



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

}//fin function










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






function index(){
		$this->layout="ajax";
		$this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo');
		$ano = $this->ano_ejecucion();
		$this->set('ano',$ano);
		$this->Session->write('cod4', $ano);
}//fin function







function mostar_cheque($pag_num=null){

$this->layout = "ajax";
$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
//$ano = $this->ano_ejecucion();
$ano = $this->Session->read('cod4');
$cond2 = "";


				if(isset($pag_num)){
						    $radio_1 =  $this->Session->read('radio');
							$cod_1   =  $this->Session->read('cod1');
							$cod_2   =  $this->Session->read('cod2');
							$cod_3   =  $this->Session->read('cod3');
							$ano     =  $this->Session->read('cod4');
							$cond    =  $this->SQLCA();
							$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria=".$cod_3." and situacion=1 ";
							$cond2   = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$cod_3."' and numero_documento=".$pag_num." and ano_movimiento='".$ano."' ";


			   }//fin



					if($cond2!=""){

									$array = $this->v_vistas_cheques_union->findAll($cond2);
								    foreach($array as $aux){
									 	$numero['ano_movimiento']              =   $aux['v_vistas_cheques_union']['ano_movimiento'];
									 	$numero['numero_cheque']               =   $aux['v_vistas_cheques_union']['numero_documento'];
									 	$numero['cod_entidad_bancaria']        =   $aux['v_vistas_cheques_union']['cod_entidad_bancaria'];
									 	$numero['cod_sucursal']                =   $aux['v_vistas_cheques_union']['cod_sucursal'];
									 	$numero['cuenta_bancaria']             =   $aux['v_vistas_cheques_union']['cuenta_bancaria'];
									 	$numero['fecha_cheque']                =   $aux['v_vistas_cheques_union']['fecha_documento'];
									 	$numero['beneficiario']                =   $aux['v_vistas_cheques_union']['beneficiario'];
									 	$numero['monto']                       =   $aux['v_vistas_cheques_union']['monto'];
									 	$numero['tipo_cheque']                 =   $aux['v_vistas_cheques_union']['tipo_cheque'];
									}



					$fecha_nueva = $numero['fecha_cheque'][8].$numero['fecha_cheque'][9].'/'.$numero['fecha_cheque'][5].$numero['fecha_cheque'][6].'/'.$numero['fecha_cheque'][0].$numero['fecha_cheque'][1].$numero['fecha_cheque'][2].$numero['fecha_cheque'][3];

									echo'<script>';
										echo"document.getElementById('beneficiar_cheque').value = '".$numero['beneficiario']."'; ";
										echo"document.getElementById('fecha_cheque').value      = '".$fecha_nueva."'; ";
										echo"document.getElementById('monto_cheque').value      = '".$this->Formato2($numero['monto'])."'; ";
										echo"document.getElementById('tipo_cheque').value      = '".$this->Formato2($numero['tipo_cheque'])."'; ";
										echo"document.getElementById('persona_receptor').readOnly = false; ";
								        echo"document.getElementById('cedula_identidad').readOnly = false; ";
									echo'</script>';


					}else{

					                echo'<script>';
										echo"document.getElementById('beneficiar_cheque').value = ''; ";
										echo"document.getElementById('fecha_cheque').value      = ''; ";
										echo"document.getElementById('monto_cheque').value      = ''; ";
										echo"document.getElementById('persona_receptor').readOnly = true; ";
								        echo"document.getElementById('cedula_identidad').readOnly = true; ";
								        echo"document.getElementById('persona_receptor').value = ''; ";
								        echo"document.getElementById('cedula_identidad').value = ''; ";
								        echo"document.getElementById('tipo_cheque').value      = ''; ";
									echo'</script>';



					}//fin else


}//fin function









function mostar_cheque2($pag_num=null){

$this->layout = "ajax";
$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
$ano = $this->ano_ejecucion();
$cond2 = "";


				if(isset($pag_num)){
						    $radio_1 =  $this->Session->read('radio');
							$cod_1   =  $this->Session->read('cod1');
							$cod_2   =  $this->Session->read('cod2');
							$cod_3   =  $this->Session->read('cod3');
							$ano     =  $this->Session->read('cod4');
							$cond    =  $this->SQLCA();
							$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria=".$cod_3." and situacion=1 ";
							$cond2   = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$cod_3."' and numero_documento=".$pag_num."  and ano_movimiento='".$ano."'";
							$cond22  = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$cod_3."' and numero_documento=".$pag_num."  and ano_movimiento='".$ano."'";


			   }//fin



					if($cond2!=""){

									$array = $this->v_vistas_cheques_union->findAll($cond2);
								    foreach($array as $aux){
									 	$numero['ano_movimiento']              =   $aux['v_vistas_cheques_union']['ano_movimiento'];
									 	$numero['numero_cheque']               =   $aux['v_vistas_cheques_union']['numero_documento'];
									 	$numero['cod_entidad_bancaria']        =   $aux['v_vistas_cheques_union']['cod_entidad_bancaria'];
									 	$numero['cod_sucursal']                =   $aux['v_vistas_cheques_union']['cod_sucursal'];
									 	$numero['cuenta_bancaria']             =   $aux['v_vistas_cheques_union']['cuenta_bancaria'];
									 	$numero['fecha_cheque']                =   $aux['v_vistas_cheques_union']['fecha_documento'];
									 	$numero['beneficiario']                =   $aux['v_vistas_cheques_union']['beneficiario'];
									 	$numero['monto']                       =   $aux['v_vistas_cheques_union']['monto'];
									 	$numero['tipo_cheque']                 =   $aux['v_vistas_cheques_union']['tipo_cheque'];
									}


									$array2 = $this->cstd05_cheques_transito->findAll($cond22);
								    foreach($array2 as $aux2){
									 	$numero2['persona_receptor']            =   $aux2['cstd05_cheques_transito']['persona_receptor'];
									 	$numero2['cedula_identidad']            =   $aux2['cstd05_cheques_transito']['cedula_identidad'];
									 	$numero2['foto']                        =   $aux2['cstd05_cheques_transito']['foto'];
									}



					$fecha_nueva = $numero['fecha_cheque'][8].$numero['fecha_cheque'][9].'/'.$numero['fecha_cheque'][5].$numero['fecha_cheque'][6].'/'.$numero['fecha_cheque'][0].$numero['fecha_cheque'][1].$numero['fecha_cheque'][2].$numero['fecha_cheque'][3];

									echo'<script>';
										echo"document.getElementById('beneficiar_cheque').value = '".$numero['beneficiario']."'; ";
										echo"document.getElementById('fecha_cheque').value      = '".$fecha_nueva."'; ";
										echo"document.getElementById('monto_cheque').value      = '".$this->Formato2($numero['monto'])."'; ";
										echo"document.getElementById('persona_receptor').value      = '".$numero2['persona_receptor']."'; ";
										echo"document.getElementById('cedula_identidad').value      = '".$numero2['cedula_identidad']."'; ";
										echo"document.getElementById('foto').value                  = '".$numero2['foto']."'; ";
										echo"document.getElementById('tipo_cheque').value      = '".$this->Formato2($numero['tipo_cheque'])."'; ";
								        echo"document.getElementById('eliminar').disabled  = false; ";
								        echo"document.getElementById('modificar').disabled = false; ";

									echo'</script>';


					}else{

					                echo'<script>';
										echo"document.getElementById('beneficiar_cheque').value = ''; ";
										echo"document.getElementById('fecha_cheque').value      = ''; ";
										echo"document.getElementById('monto_cheque').value      = ''; ";
								        echo"document.getElementById('persona_receptor').value = ''; ";
								        echo"document.getElementById('cedula_identidad').value = ''; ";
								        echo"document.getElementById('foto').value = ''; ";
								        echo"document.getElementById('tipo_cheque').value      = ''; ";
								        echo"document.getElementById('eliminar').disabled  = true; ";
								        echo"document.getElementById('modificar').disabled = true; ";
									echo'</script>';



					}//fin else


}//fin function










function guardar2(){

  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $cod_sucursal            =   $this->data["cstp05_cheques_en_transito"]["cod_sucursal"];
  $ano_movimiento          =   $this->data["cstp05_cheques_en_transito"]["ano_movimiento"];
  $cuenta_bancaria         =   $this->data["cstp05_cheques_en_transito"]["cuenta_bancaria"];
  $tipo_documento          =   $this->data["cstp05_cheques_en_transito"]["tipo_documento"];
  $numero_documento        =   $this->data["cstp05_cheques_en_transito"]["numero_documento"];
  $cod_entidad_bancaria    =   $this->data["cstp05_cheques_en_transito"]["cod_entidad_bancaria"];
  $persona_receptor       =    $this->data['cstp05_cheques_en_transito']['persona_receptor'];
  $cedula_identidad       =    $this->data['cstp05_cheques_en_transito']['cedula_identidad'];
  $tipo_cheque            =    $this->data['cstp05_cheques_en_transito']['tipo_cheque'];

               $R1 = $this->cstd05_cheques_transito->execute("BEGIN; UPDATE cstd05_cheques_transito SET persona_receptor='".$persona_receptor."', cedula_identidad='".$cedula_identidad."'  WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and numero_documento='".$numero_documento."' ");
            if($R1>1){
              	$this->cstd05_cheques_transito->execute("COMMIT;");
				$this->set('Message_existe', 'LOS DATOS FUERON MODIFICADOS');
			}else{
				$this->cstd05_cheques_transito->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON MODIFICADOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else



$this->consulta_index();
$this->render('consulta_index');
}//fin function












function modificar(){

$this->layout = "ajax";


									echo'<script>';
								        echo"document.getElementById('persona_receptor').readOnly = false; ";
								        echo"document.getElementById('cedula_identidad').readOnly = false; ";
								         echo"document.getElementById('guardar').disabled = false; ";
								         echo"document.getElementById('modificar').disabled = true; ";
								         echo"document.getElementById('eliminar').disabled = true; ";
									echo'</script>';



}//fin function








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







function select($select=null,$var=null, $var2=null) { //select codigos presupuestarios
	$this->layout = "ajax";
if($select!=null && $var!=null){
		//$cond =$this->SQLCA();
	switch($select){
		case 'sucursal':
			$this->set('SELECT','sucursal');
			$this->set('codigo','cod_sucursal');
			$this->set('seleccion','');
			$this->set('n',2);
			//$this->set('no','no');
			if($var!=null && $var=='consulta'){$this->set('consulta','consulta');}
			$this->Session->write('cod1',$var2);
			$cond =" cod_entidad_bancaria=".$var2;
			$lista = "";
			if($var2!=""){
				$busca=$this->SQLCA()." and cod_entidad_bancaria=".$var2;
				$this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'vector');
			}//fin if
		break;
		case 'cuenta':
			$this->set('SELECT','cuenta');
			$this->set('codigo','cuenta_bancaria');
			$this->set('seleccion','');
			$this->set('n',3);
			$this->set('no','no');
			$this->set('otro','otro');
			if($var!=null && $var=='consulta'){$this->set('consulta','consulta');}
			if($var!=null && $var=='consulta'){$this->set('cuenta','cuenta');}
			$this->Session->write('cod2',$var2);
			$cod_1 =  $this->Session->read('cod1');
			$cond  =  $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$var2;
			$lista = "";
			if($var2!=""){
			    $lista =  $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
			}//fin if
            if($lista==""){$lista = array(); $this->set('vector',$lista);}else{$this->set('vector',$lista);}
		break;
	}//fin wsitch
	}else{
			echo "";
	}

	                                   echo'<script>';
		                                    echo"document.getElementById('beneficiar_cheque').value = ''; ";
											echo"document.getElementById('fecha_cheque').value      = ''; ";
											echo"document.getElementById('monto_cheque').value      = ''; ";
											echo"document.getElementById('persona_receptor').readOnly = true; ";
									        echo"document.getElementById('cedula_identidad').readOnly = true; ";
									        echo"document.getElementById('persona_receptor').value = ''; ";
									        echo"document.getElementById('cedula_identidad').value = ''; ";
									        echo"document.getElementById('tipo_cheque').value      = ''; ";
								        echo'</script>';

}//fin select codigos bancarios









function select2($select=null,$var=null, $var2=null) { //select codigos presupuestarios
	$this->layout = "ajax";
if($select!=null && $var!=null){
		//$cond =$this->SQLCA();
	switch($select){
		case 'sucursal':
			$this->set('SELECT','sucursal');
			$this->set('codigo','cod_sucursal');
			$this->set('seleccion','');
			$this->set('n',2);
			//$this->set('no','no');
			if($var!=null && $var=='consulta'){$this->set('consulta','consulta');}
			$this->Session->write('cod1',$var2);
			$cond =" cod_entidad_bancaria=".$var2;
			$lista = "";
			if($var2!=""){
			  $busca=$this->SQLCA()." and cod_entidad_bancaria=".$var2;
			  $this->concatena_cuatro_digitos($this->v_cstd01_sucursales->generateList($busca, 'cod_sucursal ASC', null, '{n}.v_cstd01_sucursales.cod_sucursal', '{n}.v_cstd01_sucursales.denominacion'),'vector');
			}//fin if
		break;
		case 'cuenta':
			$this->set('SELECT','cuenta');
			$this->set('codigo','cuenta_bancaria');
			$this->set('seleccion','');
			$this->set('n',3);
			$this->set('no','no');
			$this->set('otro','otro');
			if($var!=null && $var=='consulta'){$this->set('consulta','consulta');}
			if($var!=null && $var=='consulta'){$this->set('cuenta','cuenta');}
			$this->Session->write('cod2',$var2);
			$cod_1 =  $this->Session->read('cod1');
			$cond  =  $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$var2;
			$lista = "";
			if($var2!=""){
			    $lista =  $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
			}//fin if
            if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
		break;
	}//fin wsitch
	}else{
			echo "";
	}

	                                   echo'<script>';
		                                    echo"document.getElementById('beneficiar_cheque').value = ''; ";
											echo"document.getElementById('fecha_cheque').value      = ''; ";
											echo"document.getElementById('monto_cheque').value      = ''; ";
											echo"document.getElementById('persona_receptor').readOnly = true; ";
									        echo"document.getElementById('cedula_identidad').readOnly = true; ";
									        echo"document.getElementById('persona_receptor').value = ''; ";
									        echo"document.getElementById('cedula_identidad').value = ''; ";
									        echo"document.getElementById('tipo_cheque').value      = ''; ";
								        echo'</script>';

}//fin select codigos bancarios











function generate_select_numero($var=null){



		$this->layout="ajax";
    	$i = 0;

  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


  //$year2 = $this->ano_ejecucion();
  $year2 = $this->Session->read('cod4');




	if(isset($var) && $var!=""){

		    $radio_1 =  $this->Session->read('radio');
			$cod_1   =  $this->Session->read('cod1');
			$cod_2   =  $this->Session->read('cod2');

			$this->Session->write('cod3',$var);



			$ano     =  $this->Session->read('cod4');
			$cond    =  $this->SQLCA();
			$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' ";
			$cond2   = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' ";

           $lista=  $this->v_vistas_cheques_union->generateList($cond2.' and ano_movimiento='.$year2.' and condicion_actividad=1 and status=2', 'numero_documento ASC', null, '{n}.v_vistas_cheques_union.numero_documento', '{n}.v_vistas_cheques_union.numero_documento');


	}else{$lista="";}//fin else

$this->set('lista', $lista);

                                       echo'<script>';
		                                    echo"document.getElementById('beneficiar_cheque').value = ''; ";
											echo"document.getElementById('fecha_cheque').value      = ''; ";
											echo"document.getElementById('monto_cheque').value      = ''; ";
											echo"document.getElementById('persona_receptor').readOnly = true; ";
									        echo"document.getElementById('cedula_identidad').readOnly = true; ";
									        echo"document.getElementById('persona_receptor').value = ''; ";
									        echo"document.getElementById('cedula_identidad').value = ''; ";
									        echo"document.getElementById('tipo_cheque').value      = ''; ";
								        echo'</script>';

}//fin function











function generate_select_numero2($var=null){



		$this->layout="ajax";
    	$i = 0;
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


  //$year2 = $this->ano_ejecucion();
  $year2 = $this->Session->read('cod4');




	if(isset($var) && $var!=""){

		    $radio_1 =  $this->Session->read('radio');
			$cod_1   =  $this->Session->read('cod1');
			$cod_2   =  $this->Session->read('cod2');

			$this->Session->write('cod3',$var);



			$ano     =  $this->Session->read('cod4');
			$cond    =  $this->SQLCA();
			$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' ";
			$cond2   = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' ";

           $lista=  $this->cstd05_cheques_transito->generateList($cond2.' and ano_movimiento='.$year2.'', 'numero_documento ASC', null, '{n}.cstd05_cheques_transito.numero_documento', '{n}.cstd05_cheques_transito.numero_documento');


	}else{$lista="";}//fin else

$this->set('lista', $lista);

                                       echo'<script>';
		                                    echo"document.getElementById('beneficiar_cheque').value = ''; ";
											echo"document.getElementById('fecha_cheque').value      = ''; ";
											echo"document.getElementById('monto_cheque').value      = ''; ";
											echo"document.getElementById('persona_receptor').readOnly = true; ";
									        echo"document.getElementById('cedula_identidad').readOnly = true; ";
									        echo"document.getElementById('persona_receptor').value = ''; ";
									        echo"document.getElementById('cedula_identidad').value = ''; ";
									        echo"document.getElementById('tipo_cheque').value      = ''; ";
								        echo'</script>';

}//fin function







function mostrar($opcion,$var,$codigo=null) {
	$this->layout="ajax";
	if(isset($codigo) && $codigo!=''){
	switch($opcion){
		case 'entidades':
			if(isset($var) && $var=="codigo"){
				//$c=$this->cepd03_ordenpago_tipopago->findByCod_entidad_bancaria($codigo);
				//$this->set("codigo",$c["cepd03_ordenpago_tipopago"]["cod_tipo_pago"]);
				$this->set("codigo",$codigo);
			}else if(isset($var) && $var=="deno"){
				$c=$this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($codigo);
				$this->set("deno",$c["cstd01_entidades_bancarias"]["denominacion"]);
			}
			$this->Session->write('entidad_banc', $codigo);
			echo'<script>';
			  echo"document.getElementById('codigo_select_3').innerHTML = '<br>'; ";
			  echo"document.getElementById('deno_select_3').innerHTML = '<br>'; ";
			echo'</script>';
			echo'<script>';
			  echo"document.getElementById('beneficiar_cheque').value = ''; ";
			  echo"document.getElementById('numero_documento').value = ''; ";
			  echo"document.getElementById('persona_receptor').value = ''; ";
			  echo"document.getElementById('cedula_identidad').value = ''; ";
			  echo"document.getElementById('tipo_cheque').value      = ''; ";
			echo'</script>';


	break;
		case 'sucursales':
			if(isset($var) && $var=="codigo"){
		//$c=$this->cepd03_ordenpago_tipopago->findByCod_entidad_bancaria($codigo);
		//$this->set("codigo",$c["cepd03_ordenpago_tipopago"]["cod_tipo_pago"]);
		$this->set("codigo",$codigo);
	}else if(isset($var) && $var=="deno"){
		$entidad_banc = $this->Session->read('entidad_banc');
		//$c=$this->cstd01_sucursales_bancarias->findByCod_sucursal($codigo);
		$c=$this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria = '$entidad_banc' AND cod_sucursal='$codigo'");
		$this->set("deno",$c[0]["cstd01_sucursales_bancarias"]["denominacion"]);
	}
	break;
	}
	}else{
		echo'<script>';
			  echo"document.getElementById('codigo_select_3').innerHTML = '<br>'; ";
			  echo"document.getElementById('deno_select_3').innerHTML = '<br>'; ";
			echo'</script>';
			echo'<script>';
			  echo"document.getElementById('beneficiar_cheque').value = ''; ";
			  echo"document.getElementById('numero_documento').value = ''; ";
			  echo"document.getElementById('persona_receptor').value = ''; ";
			  echo"document.getElementById('cedula_identidad').value = ''; ";
			  echo"document.getElementById('tipo_cheque').value      = ''; ";
			echo'</script>';
		echo "";
	}

}//fin mostrar













function guardar(){
  $this->layout="ajax";

  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano_movimiento         =    $this->data['cstp05_cheques_en_transito']['ano_movimiento'];
  $cod_entidad_bancaria   =    $this->data['cstp05_cheques_en_transito']['cod_entidad_bancaria'];
  $cod_sucursal           =    $this->data['cstp05_cheques_en_transito']['cod_sucursal'];
  $cuenta_bancaria        =    $this->data['cstp05_cheques_en_transito']['cuenta_bancaria'];
  $tipo_documento         =    $this->data['cstp05_cheques_en_transito']['tipo_documento'];
  $numero_documento       =    $this->data['cstp05_cheques_en_transito']['numero_documento'];
  $persona_receptor       =    $this->data['cstp05_cheques_en_transito']['persona_receptor'];
  $cedula_identidad       =    $this->data['cstp05_cheques_en_transito']['cedula_identidad'];
  $foto                   =    $this->data['cstp05_cheques_en_transito']['foto'];
  $tipo_cheque            =    $this->data['cstp05_cheques_en_transito']['tipo_cheque'];
  $fecha_transito         =    date('d/m/Y');


$sql =" BEGIN; INSERT INTO cstd05_cheques_transito (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, tipo_documento, numero_documento, persona_receptor, cedula_identidad, foto, fecha_transito) VALUES ";
$sql.=" ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_movimiento."', '".$cod_entidad_bancaria."', '".$cod_sucursal."', '".$cuenta_bancaria."', '".$tipo_documento."', '".$numero_documento."', '".$persona_receptor."', '".$cedula_identidad."', '".$foto."', '".$fecha_transito."')  ";
$sw  = $this->cstd05_cheques_transito->execute($sql);
				if($sw>1){

                   if($tipo_cheque==2){
                   	    $R1 = $this->cstd03_cheque_cuerpo->execute("UPDATE cstd03_cheque_cuerpo        SET status_cheque=3 WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and numero_cheque='".$numero_documento."'; ");
                 	}else{
                   	    $R1 = $this->cstd03_cheque_cuerpo->execute("UPDATE cstd03_movimientos_manuales SET status=3 WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and numero_documento='".$numero_documento."' and tipo_documento = 4; ");
                   	}

				                if($R1>1){
				              	$this->cstd05_cheques_transito->execute("COMMIT;");
								$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
								}else{
								$this->cstd05_cheques_transito->execute("ROLLBACK;");
								$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
								}//fin else
				}else{
				$this->cstd05_cheques_transito->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
				}//fin else
$this->index();
$this->render('index');
}//fin function








function consulta_index(){
	    $this->layout="ajax";

        $this->concatena_cuatro_digitos($this->v_cstd01_bancos->generateList($this->SQLCA(), 'cod_entidad_bancaria ASC', null, '{n}.v_cstd01_bancos.cod_entidad_bancaria', '{n}.v_cstd01_bancos.denominacion'),'tipo');
		$ano = $this->ano_ejecucion();
		$this->set('ano',$ano);
}//fin function






function eliminar(){
  $this->layout="ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $cod_sucursal            =   $this->data["cstp05_cheques_en_transito"]["cod_sucursal"];
  $ano_movimiento          =   $this->data["cstp05_cheques_en_transito"]["ano_movimiento"];
  $cuenta_bancaria         =   $this->data["cstp05_cheques_en_transito"]["cuenta_bancaria"];
  $tipo_documento          =   $this->data["cstp05_cheques_en_transito"]["tipo_documento"];
  $numero_documento        =   $this->data["cstp05_cheques_en_transito"]["numero_documento"];
  $cod_entidad_bancaria    =   $this->data["cstp05_cheques_en_transito"]["cod_entidad_bancaria"];
  $tipo_cheque            =    $this->data['cstp05_cheques_en_transito']['tipo_cheque'];



                    if($tipo_cheque==2){
                   	    $R1 = $this->cstd03_cheque_cuerpo->execute("BEGIN; UPDATE cstd03_cheque_cuerpo        SET status_cheque=2 WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and numero_cheque='".$numero_documento."'; ");
                 	}else{
                   	    $R1 = $this->cstd03_cheque_cuerpo->execute("BEGIN; UPDATE cstd03_movimientos_manuales SET status=2 WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and numero_documento='".$numero_documento."' and tipo_documento = 4; ");
                   	}

               if($R1>1){
                  $R1 = $this->cstd05_cheques_transito->execute("DELETE FROM cstd05_cheques_transito WHERE cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and cod_dep='".$cod_dep."' and cod_entidad_bancaria = '".$cod_entidad_bancaria."' and   cod_sucursal='".$cod_sucursal."' and  cuenta_bancaria='".$cuenta_bancaria."'  and tipo_documento='".$tipo_documento."' and numero_documento='".$numero_documento."'; ");


								if($R1>1){
                                   $this->cstd05_cheques_transito->execute("COMMIT;");
								   $this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS');
								}else{
								   $this->cstd05_cheques_transito->execute("ROLLBACK;");
								   $this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINADOS - POR FAVOR INTENTE DE NUEVO');
								}//fin else
				}else{
				$this->cstd05_cheques_transito->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
				}//fin else
$this->consulta_index();
$this->render('consulta_index');
}//fin function



function ano_movimiento($ano_movimiento=null){
	$this->layout = "ajax";
	$this->Session->write('cod4', $ano_movimiento);
	$this->set('Message_existe', 'EL A&Ntilde;O FUE CAMBIADO CORRECTAMENTE');
	//$this->Session->read('cod4');
}





}//fin class

?>