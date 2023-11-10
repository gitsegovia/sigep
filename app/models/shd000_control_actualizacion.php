<?php

 class shd000_control_actualizacion extends AppModel{
	var $name = 'shd000_control_actualizacion';
	var $useTable = 'shd000_control_actualizacion';


    public function condicion($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$ano,$cod_ingreso,$mes){
        $rou=rand();
        $c=parent::findCount("cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano_actualizado=$ano and cod_ingreso=$cod_ingreso and mes_actualizado=$mes and ".$rou."=".$rou);
        if($c!=0){
        	$data = parent::execute("SELECT condicion FROM shd000_control_actualizacion WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano_actualizado=$ano and cod_ingreso=$cod_ingreso and mes_actualizado=$mes and ".$rou."=".$rou.";");
            return  $data[0][0]['condicion'];
        }else{
        	parent::execute("INSERT INTO shd000_control_actualizacion VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_ingreso,$ano, $mes, 0);");
        	return '0';
        }
    }//fin


    public function _update_condicion($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$ano,$mes,$cod_ingreso){
    	return parent::execute("UPDATE shd000_control_actualizacion SET condicion=1  WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and ano_actualizado=$ano and mes_actualizado=$mes and cod_ingreso=$cod_ingreso");

    }//fin

    public function condicion2($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$ano,$cod_ingreso,$mes){
        $rou=rand();
        $c=parent::findCount("cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano_actualizado=$ano and cod_ingreso=$cod_ingreso   and mes_actualizado=$mes and ".$rou."=".$rou);
        if($c!=0){
        	$data = parent::execute("SELECT condicion FROM shd000_control_actualizacion WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano_actualizado=$ano and cod_ingreso=$cod_ingreso  and mes_actualizado=$mes and ".$rou."=".$rou.";");
            return  $data[0][0]['condicion'];
        }else{
        	return  null;
        }
    }//fin

}
?>