<?php

 class shd002_cobranza_pendiente extends AppModel{

 	var $name     = 'shd002_cobranza_pendiente';
 	var $useTable = 'shd002_cobranza_pendiente';

    public function update_insert($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$rif_ci_cobrador,$ano,$monto_mes,$mes){
    	$rand=rand();
    	//$data = parent::execute("SELECT count(*) as c FROM shd002_cobranza_pendiente WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano=$ano and rif_ci='$rif_ci_cobrador'");
        $c = parent::findCount("cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano=$ano and rif_ci='$rif_ci_cobrador' and $rand=$rand");
        $meses=array(1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre');
        if($c!=0){
            return parent::execute("UPDATE shd002_cobranza_pendiente SET ".$meses[$mes]."=".$meses[$mes]."+".$monto_mes." WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano=$ano and rif_ci='$rif_ci_cobrador'");
        }else{
        	$meses_insert=array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0);
            $meses_insert[($mes-1)]=$monto_mes;
        	$insert="INSERT INTO shd002_cobranza_pendiente VALUES($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep, '$rif_ci_cobrador',$ano, 0, ".implode(',',$meses_insert).");";
        	return parent::execute($insert);
        }
    }

    public function update_insert_restar($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$rif_ci_cobrador,$ano,$monto_mes,$mes){
    	$rand=rand();
    	//$data = parent::execute("SELECT count(*) as c FROM shd002_cobranza_pendiente WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano=$ano and rif_ci='$rif_ci_cobrador'");
        $c = parent::findCount("cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano=$ano and rif_ci='$rif_ci_cobrador' and $rand=$rand");
        $meses=array(1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre');
        //if($c!=0){
            return parent::execute("UPDATE shd002_cobranza_pendiente SET ".$meses[$mes]."=".$meses[$mes]."-".$monto_mes." WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and ano=$ano and rif_ci='$rif_ci_cobrador'");
        /*}else{
        	$meses_insert=array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0);
            $meses_insert[($mes-1)]=$monto_mes;
        	$insert="INSERT INTO shd002_cobranza_pendiente VALUES($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep, '$rif_ci_cobrador',$ano, 0, ".implode(',',$meses_insert).");";
        	return parent::execute($insert);
        }*/
    }


}
?>