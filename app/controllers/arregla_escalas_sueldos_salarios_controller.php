<?php

class ArreglaEscalasSueldosSalariosController extends AppController{

	var $name="arregla_escalas_sueldos_salarios";

    var $uses = array('cfpd31_escala_sueldos_salarios');

	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');


function arregla_escala_cfpd31_index(){

	$this->layout = "ajax";

}


function arregla_escala_cfpd31(){

	$this->layout = "ajax";

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$var_def = false;

	$condicion_cod = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

	$escala_grupo = $this->cfpd31_escala_sueldos_salarios->findAll($condicion_cod, null, null, null, null, null);

	   foreach($escala_grupo as $da){
	   		$ano_escala = $da['cfpd31_escala_sueldos_salarios']['ejercicio_fiscal'];
	   		$grupo = strtoupper($da['cfpd31_escala_sueldos_salarios']['grupo']);

    if ($grupo=="I"){ $escala=1; }
    else if ($grupo=="II"){ $escala=2; }
    else if ($grupo=="III"){ $escala=3; }
    else if ($grupo=="IV"){ $escala=4; }
    else if ($grupo=="V"){ $escala=5; }
    else if ($grupo=="VI"){ $escala=6; }
    else if ($grupo=="VII"){ $escala=7; }
    else if ($grupo=="VIII"){ $escala=8; }
    else if ($grupo=="IX"){ $escala=9; }
    else if ($grupo=="X"){ $escala=10; }
    else if ($grupo=="XI"){ $escala=11; }
    else if ($grupo=="XII"){ $escala=12; }
    else if ($grupo=="XIII"){ $escala=13; }
    else if ($grupo=="XIV"){ $escala=14; }
    else if ($grupo=="XV"){ $escala=15; }
    else if ($grupo=="XVI"){ $escala=16; }
    else if ($grupo=="XVII"){ $escala=17; }

    	$var_sql = "UPDATE cfpd31_escala_sueldos_salarios SET numero_escala=".$escala." WHERE ".$condicion_cod." AND ejercicio_fiscal=".$ano_escala." AND upper(grupo) = upper('$grupo')";
		$var_esc = $this->cfpd31_escala_sueldos_salarios->execute($var_sql);
		if($var_esc > 1){
			$var_def = true;
		}

	} // fin foreach

	if($var_def == true){
		$this->set('Message_existe', 'SE EJECUTO CORRECTAMENTE');
	}else{
		$this->set('errorMessage', 'NO SE EJECUTO');
	}

} // fin function arregla_escala_cfpd31

}
?>
