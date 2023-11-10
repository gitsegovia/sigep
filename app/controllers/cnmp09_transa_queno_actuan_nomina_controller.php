<?php

 class Cnmp09TransaQuenoActuanNominaController extends AppController{
	var $uses = array('cnmd09_transa_queno_actuan_nomina','Cnmd01','cnmd03_transacciones');
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

function index($var=null){
	//$this->layout = "ajax";
	//$listanomina=$this->cnmd01->generateList(null, 'cod_tipo_nomina ASC', null, '{n}.cnmd01.cod_tipo_nomina', '{n}.cnmd01.denominacion');
	//$this->concatena($listanomina, 'codigo');

    $this->layout = "ajax";
     $cod_presi = $this->Session->read('SScodpresi');
     $cod_entidad = $this->Session->read('SScodentidad');
     $cod_tipo_inst = $this->Session->read('SScodtipoinst');
     $cod_inst = $this->Session->read('SScodinst');
     $cod_dep = $this->Session->read('SScoddep');
     $lista = "";

    // echo $this->dateAdd(1,10,10,2008);

    $resultado= $this->Cnmd01->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.cod_tipo_nomina,
  a.denominacion

  from  Cnmd01 a where

    a.cod_presi              =   ".$cod_presi." and
    a.cod_entidad            =   ".$cod_entidad." and
    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
    a.cod_inst               =   ".$cod_inst." and
    a.cod_dep                =   ".$cod_dep." order by a.cod_tipo_nomina ASC; ");

    foreach($resultado as $ve){
       $lista[$ve[0]['cod_tipo_nomina']]=$ve[0]['cod_tipo_nomina'].' - '.$ve[0]['denominacion'];
    }//fin foreach

   $this->set( 'codigo', $lista);



}//fin index
function codigo_nomina($codigo){
	$this->layout = "ajax";
	$a = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
    $this->set("a",$a[0]['Cnmd01']['cod_tipo_nomina']);
}//fin cpcp02_codigo

function denominacion_nomina($codigo){
	$this->layout = "ajax";
	$b = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
	$this->set("b",$b[0]['Cnmd01']['denominacion']);


}//fin cpcp02_denominacion

function tipo($tipo){
	$this->layout = "ajax";
	$lista=$this->cnmd03_transacciones->generateList2('cod_tipo_transaccion='.$tipo, 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$this->concatenaN($lista, 'codigo');
	$this->set('tipo',$tipo);

}

function codi_tran($tipo,$codigo){
	$this->layout = "ajax";
	//echo $tipo.$codigo;
	$a = $this->cnmd03_transacciones->findAll("cod_tipo_transaccion=".$tipo." and cod_transaccion=".$codigo,array('cod_transaccion','denominacion'));
    $this->set("a",$a[0]['cnmd03_transacciones']['cod_transaccion']);
}//fin cpcp02_codigo

function deno_tran($tipo,$codigo){
	$this->layout = "ajax";
	$b = $this->cnmd03_transacciones->findAll("cod_tipo_transaccion=".$tipo." and cod_transaccion=".$codigo,array('cod_transaccion','denominacion'));
	$this->set("b",$b[0]['cnmd03_transacciones']['denominacion']);


}//fin cpcp02_denominacion



}
//fin class
?>
