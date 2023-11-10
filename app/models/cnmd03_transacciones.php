<?php

  class Cnmd03_transacciones extends AppModel{

 	var $name = "cnmd03_transacciones";
 	var $useTable = "cnmd03_transacciones";
 	var $primaryKey = "cod_transaccion";

    /**
     * este generateList es el estandar
     * */
	public function generateList($conditions, $order = null, $limit,$opcion = '{n}.cnmd03_transacciones.cod_transaccion', $valor= '{n}.cnmd03_transacciones.denominacion') {
	         $data = parent::generateList($conditions, 'cod_transaccion ASC', null,$opcion , $valor);
	         $lista=array();
	         if($data!=null){
		         foreach($data as $k=>$v){
		         	$lista[mascara($k,3)]=$v;
		         }
	         }else{
                $lista = array(''=>'');
	         }
	         return $lista;
		}


    /**
     * este generateList3 es para usarlo en todos los programas de seleccion
     * ejemplo el de asignacion de transacciones manuales
     * */

    public function generateList2($conditions2, $order = null, $limit,$opcion = '{n}.cnmd03_transacciones.cod_transaccion', $valor= '{n}.cnmd03_transacciones.denominacion') {
         $conditions = strtolower(trim(str_replace(' ','',$conditions2)));
         if($conditions == 'cod_tipo_transaccion=1'){
         	$conditions ="cod_tipo_transaccion = 1 and cod_transaccion!=1";
         }else{
         	$conditions = $conditions2;
         }
         //var_dump($conditions);exit();
         $data = parent::generateList($conditions, 'cod_transaccion ASC', null,$opcion , $valor);
         $lista=array();
         if($data!=null){
	         foreach($data as $k=>$v){
	         	$lista[mascara($k,3)]=$v;
	         }
         }else{
            $lista = array(''=>'');
         }
	         return $lista;
	}
	/**
	 * este generateList3 es para usarlo en todos los programas de escenarios de asignacion y deduccion
	 */
	public function generateList3($conditions2, $order = null, $limit,$opcion = '{n}.cnmd03_transacciones.cod_transaccion', $valor= '{n}.cnmd03_transacciones.denominacion') {
         $conditions = strtolower(trim(str_replace(' ','',$conditions2)));
         if($conditions == 'cod_tipo_transaccion=1'){
         	$conditions ="cod_tipo_transaccion = 1 and cod_transaccion!=1";
         }else{
         	$conditions = $conditions2;
         }
         $data = parent::generateList($conditions." AND tipo_actualizacion!=2", 'cod_transaccion ASC', null,$opcion , $valor);
         $lista=array();
         if($data!=null){
	         foreach($data as $k=>$v){
	         	$lista[mascara($k,3)]=$v;
	         }
         }else{
            $lista = array(''=>'');
         }
	         return $lista;
	}

 }

?>
