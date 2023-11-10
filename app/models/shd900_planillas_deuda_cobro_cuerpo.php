<?php

 class shd900_planillas_deuda_cobro_cuerpo extends AppModel{

 	var $name = 'shd900_planillas_deuda_cobro_cuerpo';
 	var $useTable = 'shd900_planillas_deuda_cobro_cuerpo';


    public function deuda_ano_anterior($condicion){
    	$data = parent::execute("SELECT deuda_ano_anterior FROM shd900_planillas_deuda_cobro_cuerpo WHERE ".$condicion);
    	if(count($data)>0){
            return  $data[0][0]['deuda_ano_anterior'];
    	}else{
           return 0;
    	}

    }

}
?>