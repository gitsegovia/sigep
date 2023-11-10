<?php
/*
 * Creado el 04/12/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 04:26:21 PM
 */
   class cstd02_cuentas_bancarias extends AppModel{
	var $name = 'cstd02_cuentas_bancarias';
	var $useTable = 'cstd02_cuentas_bancarias';

	public function generateList($conditions, $order = 'cod_entidad_bancaria, cod_sucursal ASC', $limit = null, $opcion = '{n}.cstd01_sucursales_bancarias.cod_sucursal', $valor= '{n}.cstd01_sucursales_bancarias.denominacion') {
		$data = parent::generateList($conditions." and (status_actividad='0' OR status_actividad='1')", $order, $limit, $opcion , $valor);

        $lista = array();
        if(count($data)>0){
	        foreach($data as $k=>$v){
	        	$codSuc = mascara($k,4);
	        	$lista[$codSuc] = $codSuc.' - '.$v;
	        }
        }else{
           $lista = array();
        }
        return $lista;
	}//fin generateList


	public function generateListTodas($conditions, $order = 'cod_entidad_bancaria, cod_sucursal ASC', $limit = null, $opcion = '{n}.cstd01_sucursales_bancarias.cod_sucursal', $valor= '{n}.cstd01_sucursales_bancarias.denominacion') {
		$data = parent::generateList($conditions, $order, $limit, $opcion , $valor);

        $lista = array();
        if(count($data)>0){
	        foreach($data as $k=>$v){
	        	$codSuc = mascara($k,4);
	        	$lista[$codSuc] = $codSuc.' - '.$v;
	        }
        }else{
           $lista = array();
        }
        return $lista;
	}//fin generateList todas

}
?>
