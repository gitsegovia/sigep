<?php
class Cnmp01AsigSumaRestaController extends AppController {
   var $name = 'cnmp01_asig_suma_resta';
   var $uses = array('suma_resta_sueldo','cnmd03_transaccion','cnmd07_transacciones_actuales','v_cnmd07_transacciones_actuales','Cnmd01', 'cnmd05', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra','ccfd04_cierre_mes');
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
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
	$this->set('nada', array());
	$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($condicion)!=0){
		$this->concatenaN($lista, 'nomina');
	}else{
		$this->set('nomina', array());
	}
 }

function codigo_nomina($codigo){
	$this->layout = "ajax";
	echo "<script>";
 		echo "document.getElementById('bt_guardar').disabled='true';  ";
 		echo "document.getElementById('codigo_asig').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		echo "document.getElementById('denominacion_asig').innerHTML='<input type=text  size=10% class=inputtext />';  ";
 	echo "</script>";
	$a = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
    $this->set("a",$a[0]['Cnmd01']['cod_tipo_nomina']);
}//fin cpcp02_codigo

function denominacion_nomina($codigo){
	$this->layout = "ajax";
	$b = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
	$this->set("b",$b[0]['Cnmd01']['denominacion']);


}//fin cpcp02_denominacion

function select_asig($codigo=null){
	$this->layout = "ajax";
	$cod=$this->SQLCA().' and cod_tipo_nomina='.$codigo.' and cod_tipo_transaccion=1';
	$lista = $this->v_cnmd07_transacciones_actuales->generateList($cod, 'cod_transaccion ASC', null, '{n}.v_cnmd07_transacciones_actuales.cod_transaccion', '{n}.v_cnmd07_transacciones_actuales.deno_transaccion');
	if($this->v_cnmd07_transacciones_actuales->findCount($cod)!=0){
		$this->concatenaN($lista, 'transa');
	}else{
		$this->set('transa', array());
	}
}

function codigo_asig($codigo=null){
	$this->layout = "ajax";
	echo "<script>";
 		echo "document.getElementById('bt_guardar').disabled='true';  ";
 	echo "</script>";
 	$this->Session->write('cod_tipo_nomina', $codigo);
	$a = $this->cnmd03_transaccion->findAll("cod_tipo_transaccion=1 and cod_transaccion=".$codigo,array('cod_transaccion','denominacion'));
    $this->set("a",$a[0]['cnmd03_transaccion']['cod_transaccion']);
}//fin cpcp02_codigo

function denominacion_asig($codigo=null){
	$this->layout = "ajax";
	$b = $this->cnmd03_transaccion->findAll("cod_tipo_transaccion=1 and cod_transaccion=".$codigo,array('cod_transaccion','denominacion'));
	$this->set("b",$b[0]['cnmd03_transaccion']['denominacion']);


}//fin cpcp02_denominacion

function procesar(){
	$this->layout = "ajax";
	$codigo_tn = $this->data['cnmp01_asig_suma_resta']['cod_tipo_nomina'];
	if($codigo_tn==null){
		$codigo_tn=0;
	}
	$codigo_as = $this->data['cnmp01_asig_suma_resta']['cod_asignacion'];
	if($codigo_as==null){
		$codigo_as=0;
	}
	$operacion = $this->data['cnmp01_asig_suma_resta']['operacion'];
	$cod=$this->SQLCA();
if($codigo_tn != 0 && $codigo_as !=0){

	echo "<script>";
 		echo "document.getElementById('bt_guardar').disabled='';  ";
 	echo "</script>";
	$b = $this->suma_resta_sueldo->findAll($this->SQLCA().' and cod_tipo_transaccion=1 and cod_transaccion='.$codigo_as.' and cod_tipo_nomina='.$codigo_tn);
	$this->set('datos',$b);
	$this->set('operacion',$operacion);
}
}
function guardar(){
	$this->layout = "ajax";
	$codigo_tn = $this->data['cnmp01_asig_suma_resta']['cod_tipo_nomina'];
	$codigo_as = $this->data['cnmp01_asig_suma_resta']['cod_asignacion'];
	$operacion = $this->data['cnmp01_asig_suma_resta']['operacion'];
	$cod=$this->SQLCA();
	$resultado=$this->cnmd07_transacciones_actuales->findAll($this->SQLCA().' and cod_tipo_transaccion=1 and cod_transaccion='.$codigo_as.' and cod_tipo_nomina='.$codigo_tn);
	 foreach($resultado as $row){
	 	$monto=$row['cnmd07_transacciones_actuales']['monto_cuota'];
	 	$cargo=$row['cnmd07_transacciones_actuales']['cod_cargo'];
	 	$ficha=$row['cnmd07_transacciones_actuales']['cod_ficha'];
	 	$cod_22 = 'cod_cargo='.$cargo.' and cod_ficha='.$ficha;
	 	if($operacion == 1){
			$actualizar='update cnmd05 set sueldo_basico= sueldo_basico + '.$monto.' where '.$cod.' and cod_tipo_nomina='.$codigo_tn.' and '.$cod_22;
		}else if($operacion == 2){
			$actualizar='update cnmd05 set sueldo_basico= sueldo_basico - '.$monto.' where '.$cod.' and cod_tipo_nomina='.$codigo_tn.' and '.$cod_22;
		}
		$this->cnmd05->execute($actualizar);
	 }

	$this->set('Message_existe', 'Operaci&oacute;n realizada con exito.');
  	$this->index();
  	$this->render("index");


}

}