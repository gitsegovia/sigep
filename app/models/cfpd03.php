<?php
  class Cfpd03 extends AppModel{

 	var $name = "cfpd03";
 	var $useTable = "cfpd03";
 	//var $primaryKey = "cod_auxiliar";



 	public function update_insert_monto_facturado($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$ano,$cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar,$monto_facturado){
        $rou=rand();
        $c=parent::findCount("cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano=$ano and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar and ".$rou."=".$rou);
        if($c!=0){
            return parent::execute("UPDATE cfpd03 SET monto_facturado=monto_facturado+$monto_facturado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano=$ano and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar and ".$rou."=".$rou);
        }else{
        	$insert="INSERT INTO cfpd03 VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $ano,$cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar,0, 0, 0, $monto_facturado,0);";
        	return parent::execute($insert);
        }
    }








 }

?>
