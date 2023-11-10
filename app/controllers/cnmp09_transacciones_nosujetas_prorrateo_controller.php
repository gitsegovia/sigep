<?php

 class Cnmp09TransaccionesNosujetasProrrateoController extends AppController{

	var $name = 'cnmp09_transacciones_nosujetas_prorrateo';
	var $uses = array('cnmd01', 'cnmd03_transacciones', 'cnmd09_transa_nosujetas_prorrateo', 'Cnmd01');
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

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');

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








function index(){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $aux ="";
 /*$resultado= $this->Cnmd01->execute("SELECT
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
			    a.cod_dep                =   ".$cod_dep." ORDER BY a.cod_tipo_nomina; ");

    foreach($resultado as $ve){

    	  if($ve[0]['cod_tipo_nomina']){
        	   $aux="0".$ve[0]['cod_tipo_nomina'];
        	}else{
	           $aux=$ve[0]['cod_tipo_nomina'];
        	}

       $lista[$ve[0]['cod_tipo_nomina']]=$aux.' - '.$ve[0]['denominacion'];
    }//fin foreach
*/
	$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
   	$this->concatenaN($lista, 'nomina');

   //$this->set('nomina', $lista);
   $cnmd03_transacciones = $this->cnmd03_transacciones->generateList2("cod_tipo_transaccion='1'  ", 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
   $this->concatenaN($cnmd03_transacciones, 'nomina2');




}//fin function













function cod_nomina($var=null){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

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
			    a.cod_dep                =   ".$cod_dep." and
			    a.cod_tipo_nomina        =   ".$var."
			    ");


echo "<script>";
        echo "document.getElementById('cod_nomina').value='".mascara_tres($resultado[0][0]['cod_tipo_nomina'])."';";
		echo "document.getElementById('deno_nomina').value='".$resultado[0][0]['denominacion']."';";
echo "</script>";


}//fin function












function cod_nomina2($var=null , $var2=null){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;



   $resultado= $this->Cnmd01->execute("SELECT
			  a.cod_tipo_transaccion,
              a.cod_transaccion,
              a.denominacion

			  from  cnmd03_transacciones a where

			    a.cod_tipo_transaccion     =    ".$var." and
			    a.cod_transaccion          =    ".$var2.";
			    ");


echo "<script>";
        echo "document.getElementById('cod_transaccion').value='".mascara_tres($resultado[0][0]['cod_transaccion'])."';";
		echo "document.getElementById('deno_transaccion').value='".$resultado[0][0]['denominacion']."';";
echo "</script>";


}//fin function








function cod_nomina3($var=null){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;





$cnmd03_transacciones = $this->cnmd03_transacciones->generateList2("cod_tipo_transaccion='".$var."'  ", 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
$this->concatenaN($cnmd03_transacciones, 'nomina2');
$this->set('tipo_nomina', $var);

echo "<script>";
        echo "document.getElementById('cod_transaccion').value='';";
		echo "document.getElementById('deno_transaccion').value='';";
echo "</script>";

}//fin function







function guardar(){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


  $cod_tipo_transaccion = $this->data['cnmp09_tan']['tipo_transaccion'];
  $cod_tipo_nomina     =  $this->data['cnmp09_tan']['cod_nomina'];
  $cod_transaccion     =  $this->data['cnmp09_tan']['cod_transaccion'];
  $cont = 0;
  $sql =" INSERT INTO cnmd09_transa_nosujetas_prorrateo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion)";
  $sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '".$cod_tipo_transaccion."', '".$cod_transaccion."'); ";

$cont = $this->cnmd09_transa_nosujetas_prorrateo->findCount($this->SQLCA()." and cod_tipo_nomina= '".$cod_tipo_nomina."' and cod_tipo_transaccion='".$cod_tipo_transaccion."' and cod_transaccion= '".$cod_transaccion."' ");

if($cont==0){
$sw2  = $this->Cnmd01->execute('BEGIN; ');
$sw2  = $this->Cnmd01->execute($sql);

                  if($sw2>1){
		                $this->Cnmd01->execute("COMMIT;");
		                $this->set('Message_existe', 'Los datos fueron guardados correctamente');


					}else{
						$this->Cnmd01->execute("ROLLBACK;");
                        $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');

					}//fin else
}else{
	$this->Cnmd01->execute("ROLLBACK;");
    $this->set('errorMessage', 'EL REGISTRO YA EXISTE');
}//fin else


  $resultado= $this->Cnmd01->execute("SELECT
			  a.cod_tipo_nomina,
			  a.cod_tipo_transaccion,
              a.cod_transaccion,
              b.cod_tipo_transaccion as cod_tipo_transaccion_cnmd03,
              b.cod_transaccion      as cod_transaccion_cnmd03,
              b.denominacion         as denominacion_cnmd03

			      from  cnmd09_transa_nosujetas_prorrateo a, cnmd03_transacciones b where

					    a.cod_presi              =   ".$cod_presi." and
					    a.cod_entidad            =   ".$cod_entidad." and
					    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
					    a.cod_inst               =   ".$cod_inst." and
					    a.cod_dep                =   ".$cod_dep." and
					    a.cod_tipo_nomina        =   ".$cod_tipo_nomina." and
					    b.cod_tipo_transaccion   =   a.cod_tipo_transaccion and
                        b.cod_transaccion        =   a.cod_transaccion;
			    ");

$this->set('accion', $resultado);


}//fin function














function consulta($var=null){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


  $resultado= $this->Cnmd01->execute("SELECT
			  a.cod_tipo_nomina,
			  a.cod_tipo_transaccion,
              a.cod_transaccion,
              b.cod_tipo_transaccion as cod_tipo_transaccion_cnmd03,
              b.cod_transaccion      as cod_transaccion_cnmd03,
              b.denominacion         as denominacion_cnmd03

			      from  cnmd09_transa_nosujetas_prorrateo a, cnmd03_transacciones b where

					    a.cod_presi              =   ".$cod_presi." and
					    a.cod_entidad            =   ".$cod_entidad." and
					    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
					    a.cod_inst               =   ".$cod_inst." and
					    a.cod_dep                =   ".$cod_dep." and
					    a.cod_tipo_nomina        =   ".$var." and
					    b.cod_tipo_transaccion   =   a.cod_tipo_transaccion and
                        b.cod_transaccion        =   a.cod_transaccion;
			    ");

$this->set('accion', $resultado);


}//fin function












function eliminar($var=null, $var2=null, $var3=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


  $sql="BEGIN;  DELETE FROM cnmd09_transa_nosujetas_prorrateo  WHERE cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$var."' and cod_tipo_transaccion='".$var2."' and cod_transaccion= '".$var3."' ";

  $sw2 = $this->Cnmd01->execute($sql);
			if($sw2>1){
                $this->Cnmd01->execute("COMMIT;");
		        $this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS CORRECTAMENTE');
			}else{
				$this->Cnmd01->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINADOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else



$resultado= $this->Cnmd01->execute("SELECT
			  a.cod_tipo_nomina,
			  a.cod_tipo_transaccion,
              a.cod_transaccion,
              b.cod_tipo_transaccion as cod_tipo_transaccion_cnmd03,
              b.cod_transaccion      as cod_transaccion_cnmd03,
              b.denominacion         as denominacion_cnmd03

			      from  cnmd09_transa_nosujetas_prorrateo a, cnmd03_transacciones b where

					    a.cod_presi              =   ".$cod_presi." and
					    a.cod_entidad            =   ".$cod_entidad." and
					    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
					    a.cod_inst               =   ".$cod_inst." and
					    a.cod_dep                =   ".$cod_dep." and
					    a.cod_tipo_nomina        =   ".$var." and
					    b.cod_tipo_transaccion   =   a.cod_tipo_transaccion and
                        b.cod_transaccion        =   a.cod_transaccion;
			    ");

$this->set('accion', $resultado);



}//fin funtion










}//fin class

?>