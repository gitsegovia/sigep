<?php
class Cnmp01TransaMultiplicaDivideController extends AppController {
   var $name = 'cnmp01_transa_multiplica_divide';
   var $uses = array('cnmd03_transaccion','cnmd07_transacciones_actuales','v_cnmd07_transacciones_actuales','Cnmd01', 'cnmd05', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra','ccfd04_cierre_mes');
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
   }//fin AddCero

   function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;

}

function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		//print_r($cod);

		$this->set($nomVar, $cod);

	}
}


function index(){
	$this->layout = "ajax";
	$this->data=null;
	$this->Session->delete('cod_tipo_nomina');
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
	$this->set('uno', array());
	$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($condicion)!=0){
		$this->concatenaN($lista, 'nomina');
	}else{
		$this->set('nomina', array());
	}
 }

function codigo_nomina($codigo){
	$this->layout = "ajax";
		$this->Session->delete('cod_tipo_nomina');
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
	$this->layout = "ajax";
	if($codigo==null){
	$codigo = $this->Session->read('cod_tipo_nomina');
	}
	//echo $this->SQLCA().' and cod_tipo_nomina='.$codigo.' and cod_tipo_transaccion='.$tipo;
	$lista = $this->v_cnmd07_transacciones_actuales->generateList($this->SQLCA().' and cod_tipo_nomina='.$codigo.' and cod_tipo_transaccion='.$tipo, 'cod_transaccion ASC', null, '{n}.v_cnmd07_transacciones_actuales.cod_transaccion', '{n}.v_cnmd07_transacciones_actuales.deno_transaccion');
	if($this->v_cnmd07_transacciones_actuales->findCount($this->SQLCA().' and cod_tipo_nomina='.$codigo.' and cod_tipo_transaccion='.$tipo)!=0){
		$this->concatenaN($lista, 'transa');
	}else{
		$this->set('transa', array());
	}
	$this->set('tipo',$tipo);
	$this->set('codigo',$codigo);
		echo "<script>";
		  		echo "document.getElementById('codigo_transa').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('denominacion_transa').innerHTML='<input type=text  size=10% class=inputtext />';  ";
       	echo "</script>";
}


function codigo_transa($tipo=null,$codigo=null){
	$this->layout = "ajax";
	//echo "cod_tipo_transaccion=".$tipo." and cod_transaccion=".$codigo;
	$a = $this->cnmd03_transaccion->findAll("cod_tipo_transaccion=".$tipo." and cod_transaccion=".$codigo,array('cod_transaccion','denominacion'));
    $this->set("a",$a[0]['cnmd03_transaccion']['cod_transaccion']);
}//fin cpcp02_codigo

function denominacion_transa($tipo=null,$codigo=null){
	$this->layout = "ajax";
	$b = $this->cnmd03_transaccion->findAll("cod_tipo_transaccion=".$tipo." and cod_transaccion=".$codigo,array('cod_transaccion','denominacion'));
	$this->set("b",$b[0]['cnmd03_transaccion']['denominacion']);


}//fin cpcp02_denominacion


function grilla($tipo=null,$codigo=null){
	$this->layout = "ajax";
	$codi = $this->Session->read('cod_tipo_nomina');
	$cod=$this->SQLCA().' and cod_tipo_nomina='.$codi.' and cod_tipo_transaccion='.$tipo.' and cod_transaccion='.$codigo;
	$b = $this->v_cnmd07_transacciones_actuales->findAll($cod,null,'cod_cargo,cod_puesto ASC');
	$this->set('datos',$b);
	//print_r($b);
}

function procesar(){
	$this->layout = "ajax";
//	pr($this->data);
	$codigo = $this->data['cnmp01_transa_multiplica_divide']['cod_tipo_nomina'];
	$tipo = $this->data['cnmp01_transa_multiplica_divide']['tipo'];
	$cod_transa = $this->data['cnmp01_transa_multiplica_divide']['cod_transa'];
	$operacion = $this->data['cnmp01_transa_multiplica_divide']['operacion'];
	$cuanto = $this->data['cnmp01_transa_multiplica_divide']['cuanto'];
	$cod=$this->SQLCA().' and cod_tipo_nomina='.$codigo.' and cod_tipo_transaccion='.$tipo.' and cod_transaccion='.$cod_transa;
	//echo $cod;
	$b = $this->v_cnmd07_transacciones_actuales->findAll($cod,null,'cod_cargo,cod_puesto ASC');
	$this->set('datos',$b);
	$this->set('operacion',$operacion);
	$this->set('cuanto',$cuanto);
}
function guardar(){
	$this->layout = "ajax";
	$codigo = $this->data['cnmp01_transa_multiplica_divide']['cod_tipo_nomina'];
	$tipo = $this->data['cnmp01_transa_multiplica_divide']['tipo'];
	$cod_transa = $this->data['cnmp01_transa_multiplica_divide']['cod_transa'];
	$operacion = $this->data['cnmp01_transa_multiplica_divide']['operacion'];
	$cuanto = $this->data['cnmp01_transa_multiplica_divide']['cuanto'];
	$cod=$this->SQLCA().' and cod_tipo_nomina='.$codigo.' and cod_tipo_transaccion='.$tipo.' and cod_transaccion='.$cod_transa;
	$username = $this->Session->read('nom_usuario');
	if($operacion == 3){
	$actualizar="update cnmd07_transacciones_actuales set monto_cuota= monto_cuota * ".$cuanto." , username='$username' where ".$cod;
	$actualizar2="update cnmd07_transacciones_prenomina set monto_cuota= monto_cuota * ".$cuanto." , username='$username' where ".$cod;
	}else if($operacion == 4){
	$actualizar="update cnmd07_transacciones_actuales set monto_cuota= monto_cuota / ".$cuanto.",  username='$username' where ".$cod;
	$actualizar2="update cnmd07_transacciones_prenomina set monto_cuota= monto_cuota / ".$cuanto.",  username='$username' where ".$cod;
	//echo $actualizar;
	}
	$b = $this->cnmd05->execute($actualizar);
	$b2 = $this->cnmd05->execute($actualizar2);
	$this->set('Message_existe', 'Operaci&oacute;n realizada con exito.');
  	$this->index();
  	$this->render("index");


}

function operacion($ope=null){
	$this->layout = "ajax";
	$this->set('ope',$ope);
}
function nuevo($codigo){
	$this->layout = "ajax";
	$this->set('ope',$codigo);
}
}