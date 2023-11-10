<?php

 class v_shd900_cobranza_acumulada_ano_mes_dia extends AppModel{
	var $name = 'v_shd900_cobranza_acumulada_ano_mes_dia';
	var $useTable = 'v_shd900_cobranza_acumulada_ano_mes_dia';


    public function ano($condicion){
    	$data = parent::execute("SELECT DISTINCT ano FROM v_shd900_cobranza_acumulada_ano_mes_dia WHERE ".$condicion);
    	foreach($data as $l){
				$v[]=$l[0]['ano'];
				$d[]=$l[0]['ano'];
			}
			if(isset($v) && count($v)!=0){
				$lista = array_combine($v, $d);
			}else{
				$v[]="";
				$lista = array_combine($v, $v);
			}
			return  $lista;
    }

    public function mes($condicion){
    	$data = parent::execute("SELECT DISTINCT mes FROM v_shd900_cobranza_acumulada_ano_mes_dia WHERE ".$condicion);
    	$meses=array('1'=>'enero','2'=>'febrero','3'=>'marzo','4'=>'abril','5'=>'mayo','6'=>'junio','7'=>'julio','8'=>'agosto','9'=>'septiembre','10'=>'octubre','11'=>'noviembre','12'=>'diciembre');
    	foreach($data as $l){
				$v[]=$l[0]['mes'];
				$d[]=$meses[$l[0]['mes']];
			}
			if(isset($v) && count($v)!=0){
				$lista = array_combine($v, $d);
			}else{
				$v[]="";
				$lista = array_combine($v, $v);
			}
			return  $lista;
    }

    public function dia($condicion){
    	$data = parent::execute("SELECT DISTINCT dia FROM v_shd900_cobranza_acumulada_ano_mes_dia WHERE ".$condicion);
    	foreach($data as $l){
				$v[]=$l[0]['dia'];
				$d[]=$l[0]['dia'];
			}
			if(isset($v) && count($v)!=0){
				$lista = array_combine($v, $d);
			}else{
				$v[]="";
				$lista = array_combine($v, $v);
			}
			return  $lista;
    }


}
?>