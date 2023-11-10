<?php

 class shd000_control_numero extends AppModel{
	var $name = 'shd000_control_numero';
	var $useTable = 'shd000_control_numero';


    public function numero_planilla($condicion){
    	$rou=rand();
    	$c=parent::findCount($condicion." and ".$rou."=".$rou);
        if($c!=0){
        	$data = parent::execute("SELECT numero_planilla FROM shd000_control_numero WHERE ".$condicion." and ".$rou."=".$rou);
            return  $data[0][0]['numero_planilla'];
        }else{
        	//parent::execute("SELECT numero_planilla FROM shd000_control_numero WHERE ".$condicion)
        	return 0;
        }
    }

    public function update_insert_planilla($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$ano,$cod_ingreso,$numero_planilla){
        $rou=rand();
    	$c=parent::findCount("cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano=$ano and cod_ingreso=$cod_ingreso and ".$rou."=".$rou);
        if($c!=0){
            return parent::execute("UPDATE shd000_control_numero SET numero_planilla=$numero_planilla  WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano=$ano and cod_ingreso=$cod_ingreso and ".$rou."=".$rou);
        }else{
        	return parent::execute("INSERT INTO shd000_control_numero VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep,$ano, $cod_ingreso,$numero_planilla);");
        }
    }

}
?>