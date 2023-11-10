<?php
/*
 * Creado el 30/11/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 10:20:30 PM
 */

 class cstd01_entidades_bancarias extends AppModel{
	var $name = 'cstd01_entidades_bancarias';
	var $useTable = 'cstd01_entidades_bancarias';

	public function generateList($conditions, $order = 'cod_entidad_bancaria ASC', $limit = null, $opcion = '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', $valor= '{n}.cstd01_entidades_bancarias.denominacion') {
		$data = parent::generateList($conditions, $order, $limit, $opcion , $valor);

        $lista = array();
        if(count($data)>0){
	        foreach($data as $k=>$v){
	        	$codEnt = mascara($k,4);
	        	$lista[$codEnt] = $codEnt.' - '.$v;
	        }
        }else{
           $lista = array();
        }
        return $lista;
	}//fin generateList

}
?>
