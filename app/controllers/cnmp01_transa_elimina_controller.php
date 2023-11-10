<?php
class Cnmp01TransaEliminaController extends AppController {
   var $name = 'cnmp01_transa_elimina';
   var $uses = array('cnmd03_transaccion','cnmd07_transacciones_actuales','v_cnmd07_transacciones_actuales','Cnmd01', 'cnmd08_historia_nomina', 'v_cnmd08_historia_transacciones', 'cnmd05', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra','ccfd04_cierre_mes');
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

function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector != null){
			if($extra==null){
			foreach($vector as $x){
				if($x<10){
				$Var[$x]="00".$x;
				} else if($x>=10 && $x<=99){
				$Var[$x]="0".$x;
				}else{
				$Var[$x]=$x;
				}
			}//fin each
		}else{
			foreach($vector as $x){
				if($x<10){
				$Var[$x]="00".$x;
				} else if($x>=10 && $x<=99){
				$Var[$x]="0".$x;
				}else{
				$Var[$x]=$x;
				}
			}//fin each
		}
		$this->set($nomVar,$Var);
   	  }else{
   	  	$this->set($nomVar,'');
   	  }
   }//fin AddCero

   function zero($x=null){
	if($x != null){
		if($x<10){
			$x="00".$x;
		}else if($x>=10 && $x<=99){
			$x="0".$x;
		}
	}
	return $x;

}

function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - - '.$y;
		}

		$this->set($nomVar, $cod);

	}
}


function index(){
	$this->layout = "ajax";
	$this->data=null;
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
	$this->set('uno', array());
	$lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina IN (0,1)", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($condicion)!=0){
		$this->concatenaN($lista, 'nomina');
	}else{
		$this->set('nomina', array());
	}
 }

function codigo_nomina($codigo){
	$this->layout = "ajax";
 		 $this->Session->write('cod_tipo_nomina', $codigo);
		 $a = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
    	 $this->set("a",$a[0]['Cnmd01']['cod_tipo_nomina']);
 		 echo "<script>";
 		 		echo "document.getElementById('radio_1').disabled='';  ";
 		 		echo "document.getElementById('radio_2').disabled='';  ";
 		 		echo "document.getElementById('radio_1').checked=true;  ";
 		 		echo "document.getElementById('radio_2').checked=false;  ";
		  		echo "document.getElementById('codigo_transa').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('denominacion_transa').innerHTML='<input type=text  size=10% class=inputtext />';  ";
       	 echo "</script>";
}//fin cpcp02_codigo

function denominacion_nomina($codigo){
	$this->layout = "ajax";
	$b = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
	$this->set("b",$b[0]['Cnmd01']['denominacion']);


}//fin cpcp02_denominacion

function radio($tipo=null,$codigo=null){
	$this->layout = "ajax";//echo 'si';
	if($codigo==null){
	$codigo = $this->Session->read('cod_tipo_nomina');
	}
	/////////////////////////////////////////////////////////////
	$datos  = $this->v_cnmd07_transacciones_actuales->execute(" SELECT DISTINCT cod_transaccion,deno_transaccion FROM v_cnmd07_transacciones_actuales WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$codigo.' and cod_tipo_transaccion='.$tipo." ORDER BY cod_transaccion ASC");
	if(count($datos)!=0){
		foreach($datos as $n){
			$lista[$n[0]['cod_transaccion']]=mascara($n[0]['cod_transaccion'],3)." - ".$n[0]['deno_transaccion'];
	    }
	}else{
		$lista=array('0'=>'');
	}
	$this->set("transa", $lista);
	$this->set('tipo',$tipo);
	$this->set('codigo',mascara($codigo,3));
		echo "<script>";
		  		echo "document.getElementById('codigo_transa').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('denominacion_transa').innerHTML='<input type=text  size=10% class=inputtext />';  ";
       	echo "</script>";
}


function codigo_transa($tipo=null,$codigo=null){
	$this->layout = "ajax";
	$a = $this->cnmd03_transaccion->findAll("cod_tipo_transaccion=".$tipo." and cod_transaccion=".$codigo,array('cod_transaccion','denominacion'));
    $this->set("a",mascara($a[0]['cnmd03_transaccion']['cod_transaccion'],3));
}//fin


function denominacion_transa($tipo=null,$codigo=null){
	$this->layout = "ajax";
	$b = $this->cnmd03_transaccion->findAll("cod_tipo_transaccion=".$tipo." and cod_transaccion=".$codigo,array('cod_transaccion','denominacion'));
	$this->set("b",$b[0]['cnmd03_transaccion']['denominacion']);
}//fin


function grilla($tipo=null,$codigo=null){
	$this->layout = "ajax";//echo 'hola';
	$cod_tipo_nomina = $this->Session->read('cod_tipo_nomina');
	$cod=$this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_tipo_transaccion='.$tipo.' and cod_transaccion='.$codigo;
	$b = $this->v_cnmd07_transacciones_actuales->findAll($cod,null,'cod_cargo,cod_puesto ASC');
	$this->set('datos',$b);
}

function procesar(){
	$this->layout = "ajax";
//	pr($this->data);
	$codigo = $this->data['cnmp01_transa_elimina']['cod_tipo_nomina'];
	$tipo = $this->data['cnmp01_transa_elimina']['tipo'];
	$cod_transa = $this->data['cnmp01_transa_elimina']['cod_transa'];
	$cod=$this->SQLCA().' and cod_tipo_nomina='.$codigo.' and cod_tipo_transaccion='.$tipo.' and cod_transaccion='.$cod_transa;
	$elimina  =' DELETE FROM cnmd07_transacciones_actuales WHERE '.$cod.";";
	$elimina .=' DELETE FROM cnmd07_transacciones_prenomina WHERE '.$cod.";";
	$elimina .=' DELETE FROM cnmd07_transacciones_suspendidas WHERE '.$cod.";";
	//echo $elimina;
	$b = $this->cnmd07_transacciones_actuales->execute($elimina);
	$this->set('Message_existe', 'Operaci&oacute;n realizada con exito.');
  	$this->index();
  	$this->render("index");

}
function guardar(){
	$this->layout = "ajax";
	$codigo = $this->data['cnmp01_transa_elimina']['cod_tipo_nomina'];
	$tipo = $this->data['cnmp01_transa_elimina']['tipo'];
	$cod_transa = $this->data['cnmp01_transa_elimina']['cod_transa'];
	$operacion = $this->data['cnmp01_transa_elimina']['operacion'];
	$cuanto = $this->data['cnmp01_transa_elimina']['cuanto'];
	$cod=$this->SQLCA().' and cod_tipo_nomina='.$codigo.' and cod_tipo_transaccion='.$tipo.' and cod_transaccion='.$cod_transa;
	if($operacion == 1){
	$actualizar='update cnmd07_transacciones_actuales set monto_cuota= monto_cuota * '.$cuanto.' where '.$cod;
	}else if($operacion == 2){
	$actualizar='update cnmd07_transacciones_actuales set monto_cuota= monto_cuota / '.$cuanto.' where '.$cod;
	//echo $actualizar;
	}
	$b = $this->cnmd05->execute($actualizar);
	$this->set('Message_existe', 'Operaci&oacute;n realizada con exito.');
  	$this->index();
  	$this->render("index");


}









// ******** PASAR TRANSACCIONES DE HISTORIA A NOMINAS *********


function pasa_transacciones_historia_a_nomina(){
	$this->layout = "ajax";
	$this->data=null;
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
	$lista = $this->Cnmd01->generateList($condicion." and status_nomina IN (0,1)", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($condicion)!=0){
		$this->concatenaN($lista, 'nomina');
	}else{
		$this->set('nomina', array());
	}
}


function cod_deno_nomina($codigo=null){
	$this->layout = "ajax";
	$a = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo, array('cod_tipo_nomina','denominacion'));

 		 echo "<script>";
 		 		echo "document.getElementById('codigo_nomina').value='".mascara($a[0]['Cnmd01']['cod_tipo_nomina'], 3)."';
 		 			  document.getElementById('denominacion_nomina').value='".$a[0]['Cnmd01']['denominacion']."';
 		 			  document.getElementById('numero_nomina').value='';
 		 			  document.getElementById('concepto').value='';
 		 			  document.getElementById('periodo_desde').value='';
					  document.getElementById('periodo_hasta').value='';
					  document.getElementById('radio_1').checked=false;
					  document.getElementById('radio_2').checked=false;
					  document.getElementById('radio_1').disabled=true;
					  document.getElementById('radio_2').disabled=true;
					  document.getElementById('select_transac').innerHTML='<select></select>';
 		 			  document.getElementById('codigo_transa').value='';
					  document.getElementById('denominacion_transa').value='';";
		echo "</script>";
}//fin function


function numeros_nomina($codigo=null){
	$this->layout = "ajax";
	$this->set('cod_nomina', $codigo);
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
	$lista = $this->cnmd08_historia_nomina->generateList($condicion." and cod_tipo_nomina=$codigo", 'numero_nomina ASC', null, '{n}.cnmd08_historia_nomina.numero_nomina', '{n}.cnmd08_historia_nomina.concepto');
	if(!empty($lista)){
		$this->concatena_cuatro_digitos($lista, 'numeros_nomina');
	}else{
		$this->set('numeros_nomina', array());
	}
}


function datos_num_nom($codigo=null, $numero=null){
	$this->layout = "ajax";
	$d = $this->cnmd08_historia_nomina->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo." and numero_nomina=$numero", array('numero_nomina','periodo_desde','periodo_hasta','concepto'));

 		 echo "<script>";
 		 		echo "document.getElementById('numero_nomina').value='".mascara($d[0]['cnmd08_historia_nomina']['numero_nomina'], 4)."';
 		 			  document.getElementById('concepto').value='".$d[0]['cnmd08_historia_nomina']['concepto']."';
 		 			  document.getElementById('periodo_desde').value='".cambia_fecha($d[0]['cnmd08_historia_nomina']['periodo_desde'])."';
					  document.getElementById('periodo_hasta').value='".cambia_fecha($d[0]['cnmd08_historia_nomina']['periodo_hasta'])."';";
       	 echo "</script>";
}//fin function


function transaccion($codigo=null,$numero_nom=null,$tipo=null,$cod_trans=null){
	$this->layout = "ajax";
	$datos = $this->v_cnmd08_historia_transacciones->execute("SELECT DISTINCT cod_transaccion, deno_transaccion FROM v_cnmd08_historia_transacciones WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$codigo.' and numero_nomina='.$numero_nom.' and cod_tipo_transaccion='.$tipo." and cod_transaccion=$cod_trans;");
 		 echo "<script>";
 		 		echo "document.getElementById('codigo_transa').value='".mascara($datos[0][0]['cod_transaccion'], 3)."';
					  document.getElementById('denominacion_transa').value='".$datos[0][0]['deno_transaccion']."';";
       	 echo "</script>";
}


function tipo_trans($var=null, $codigo=null,$numero_nom=null,$tipo=null){
	$this->layout = "ajax";
	$this->set('var', $var);
	if((int)$var==1){
		$this->set('cod_nomina', $codigo);
		$this->set('numero_nom', $numero_nom);
 		 echo "<script>";
 		 		echo "document.getElementById('select_transac').innerHTML='<select></select>';
 		 			  document.getElementById('codigo_transa').value='';
					  document.getElementById('denominacion_transa').value='';";
       	 echo "</script>";
	}else{

	$datos = $this->v_cnmd08_historia_transacciones->execute("SELECT DISTINCT cod_transaccion, deno_transaccion FROM v_cnmd08_historia_transacciones WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$codigo.' and numero_nomina='.$numero_nom.' and cod_tipo_transaccion='.$tipo." ORDER BY cod_transaccion ASC;");
	if(count($datos)!=0){
		foreach($datos as $n){
			$lista[$n[0]['cod_transaccion']]=mascara($n[0]['cod_transaccion'],3)." - ".$n[0]['deno_transaccion'];
	    }
	}else{
		$lista=array('0'=>'');
	}
	$this->set('cod_nomina', $codigo);
	$this->set('numero_nom', $numero_nom);
	$this->set('tipo', $tipo);
	$this->set("transa", $lista);

 		 echo "<script>";
 		 		echo "document.getElementById('codigo_transa').value='';
					  document.getElementById('denominacion_transa').value='';";
       	 echo "</script>";
	}
}


function guardar_paso_trans(){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$codigo = $this->data['cnmp01_transa_elimina']['cod_tipo_nomina'];
	$cod_numero_nom = $this->data['cnmp01_transa_elimina']['cod_numero_nom'];
	$tipo = $this->data['cnmp01_transa_elimina']['radio'];
	$cod_transa = $this->data['cnmp01_transa_elimina']['cod_transa'];
	$cond=$this->SQLCA().' and cod_tipo_nomina='.$codigo.' and numero_nomina='.$cod_numero_nom.' and cod_tipo_transaccion='.$tipo.' and cod_transaccion='.$cod_transa;

	$trans_actual = $this->v_cnmd07_transacciones_actuales->execute("SELECT cod_transaccion FROM cnmd07_transacciones_actuales WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$codigo.' and cod_tipo_transaccion='.$tipo.' and cod_transaccion='.$cod_transa." LIMIT 1;");
	$trans_prenomina = $this->v_cnmd07_transacciones_actuales->execute("SELECT cod_transaccion FROM cnmd07_transacciones_prenomina WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$codigo.' and cod_tipo_transaccion='.$tipo.' and cod_transaccion='.$cod_transa." LIMIT 1;");



	if(!empty($trans_actual)){ // Verificando que la transaccion existe en transac. actuales de la Nomina Actual - TRUE:NO EJECUTA


		$this->set('errorMessage', 'Esta transacci&oacute;n ya se encuentra registrada [Transacciones Actuales]');




	}else if(!empty($trans_prenomina)){ // Verificando que la transaccion existe en transac. prenomina de la Nomina Actual - TRUE:NO EJECUTA

		$this->set('errorMessage', 'Esta transacci&oacute;n ya se encuentra registrada [Transacciones Prenomina]');




	}else{ // Ejecuta el Proceso

		$dtransacciones = $this->v_cnmd08_historia_transacciones->execute("SELECT cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion, fecha_transaccion, monto_original, numero_cuotas_descontar, numero_cuotas_canceladas, monto_cuota, saldo, dias_horas FROM v_cnmd08_historia_transacciones WHERE ".$cond." ORDER BY cod_transaccion ASC;");

	if(!empty($dtransacciones)){

		$sqli = 0;
		$sqli2 = 0;

		foreach($dtransacciones as $transacciones){
			$trans_actualr = $this->v_cnmd07_transacciones_actuales->execute("SELECT cod_transaccion FROM cnmd07_transacciones_actuales WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$codigo.' and cod_cargo='.$transacciones[0]['cod_cargo'].' and cod_ficha='.$transacciones[0]['cod_ficha'].' and cod_tipo_transaccion='.$tipo.' and cod_transaccion='.$cod_transa." LIMIT 1;");
			$trans_prenominar = $this->v_cnmd07_transacciones_actuales->execute("SELECT cod_transaccion FROM cnmd07_transacciones_prenomina WHERE ".$this->SQLCA().' and cod_tipo_nomina='.$codigo.' and cod_cargo='.$transacciones[0]['cod_cargo'].' and cod_ficha='.$transacciones[0]['cod_ficha'].' and cod_tipo_transaccion='.$tipo.' and cod_transaccion='.$cod_transa." LIMIT 1;");

			if(empty($trans_actualr)){
				$sqli = $this->v_cnmd08_historia_transacciones->execute("INSERT INTO cnmd07_transacciones_actuales VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $codigo, '".$transacciones[0]['cod_cargo']."', '".$transacciones[0]['cod_ficha']."', '".$transacciones[0]['cod_tipo_transaccion']."', '".$transacciones[0]['cod_transaccion']."', '".$transacciones[0]['fecha_transaccion']."', '".$transacciones[0]['monto_original']."', '".$transacciones[0]['numero_cuotas_descontar']."', 1, '".$transacciones[0]['numero_cuotas_canceladas']."', '".$transacciones[0]['monto_cuota']."', '".$transacciones[0]['saldo']."', '', '".date("Y-m-d")."', '".$_SESSION["nom_usuario"]."', '".$transacciones[0]['dias_horas']."');");
			}else{
				// AQUI SE PUEDE COLOCAR LA LOGICA PARA ACTUALIZAR LA TRANSACCION ACTUAL (hay que modificar en la busqueda superior $trans_actual)
			}

			if(empty($trans_prenominar)){
				$sqli2 = $this->v_cnmd08_historia_transacciones->execute("INSERT INTO cnmd07_transacciones_prenomina VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $codigo, '".$transacciones[0]['cod_cargo']."', '".$transacciones[0]['cod_ficha']."', '".$transacciones[0]['cod_tipo_transaccion']."', '".$transacciones[0]['cod_transaccion']."', '".$transacciones[0]['fecha_transaccion']."', '".$transacciones[0]['monto_original']."', '".$transacciones[0]['numero_cuotas_descontar']."', 1, '".$transacciones[0]['numero_cuotas_canceladas']."', '".$transacciones[0]['monto_cuota']."', '".$transacciones[0]['saldo']."', '', '".date("Y-m-d")."', '".$_SESSION["nom_usuario"]."', '".$transacciones[0]['dias_horas']."');");
			}else{
				// AQUI SE PUEDE COLOCAR LA LOGICA PARA ACTUALIZAR LA TRANSACCION PRENOMINA (hay que modificar en la busqueda superior en $trans_prenomina)
			}
		} // fin foreach


		if($sqli > 1 || $sqli2 > 1){

			$tipo_nom1 = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo, 'status_nomina', null, 1);
			if(!empty($tipo_nom1)){
				if($tipo_nom1[0]['Cnmd01']['status_nomina'] == 1){
					$this->Cnmd01->execute("UPDATE cnmd01 SET status_nomina = 0 WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$codigo);
				}
			}

			$this->set('Message_existe', 'Operaci&oacute;n realizada con exito!!');

			echo "<script type='text/javascript'>
					document.getElementById('cod_transa').options[1].selected = true;
					document.getElementById('codigo_transa').value='';
					document.getElementById('denominacion_transa').value='';
			</script>";

		}else{
			$this->set('errorMessage', 'La Operaci&oacute;n no pudo ser realizada!!');
		}


	}else{

		$this->set('errorMessage', 'No se encontraron transacciones en la historia para procesar!!');
	}

	}// fin ejecucion del proceso


	echo "<script type='text/javascript'>
			document.getElementById('bt_procesar').disabled=false;
		</script>";

}// fin funcion guardar_paso_trans

}
?>
