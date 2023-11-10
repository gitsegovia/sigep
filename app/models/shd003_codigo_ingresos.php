<?php

 class shd003_codigo_ingresos extends AppModel{

 	var $name = 'shd003_codigo_ingresos';
 	var $useTable = 'shd003_codigo_ingresos';

    public function codigos($condicion){
    	$data = parent::execute("SELECT cod_partida,cod_generica,cod_especifica,cod_subespec,cod_auxiliar,denominacion FROM shd003_codigo_ingresos WHERE ".$condicion);
    	if(count($data)>0){
            return  $data;
    	}else{

           return null;
    	}

    }


}
?>