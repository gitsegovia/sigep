<?php
/*
 * Fecha: 15/06/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * cake
 */

  class Cnmd03_transaccion extends AppModel{

 	var $name = "cnmd03_transaccion";
 	var $useTable = "cnmd03_transacciones";
 	var $primaryKey = "cod_transaccion";


    public function generateList($conditions, $order = null, $limit,$opcion = '{n}.cnmd03_transacciones.cod_transaccion', $valor= '{n}.cnmd03_transacciones.denominacion') {
	         $data = parent::generateList($conditions, 'cod_transaccion ASC', null,$opcion , $valor);
	         $lista=array();
	         if(count($data)>0){
		         foreach($data as $k=>$v){
		         	$lista[mascara($k,3)]=$v;
		         }
	         }else{
                $lista = array();
	         }
	         return $lista;
	}
 }

?>
