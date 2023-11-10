<?php
 class casd01_comunicacion_invitacion extends AppModel{
 	var $name='casd01_comunicacion_invitacion';
 	var $useTable='casd01_comunicacion_invitacion';

 function nuevo_numero_oficio($condicion=null){
 	$data = $this->findAll($condicion,'numero_oficio','numero_oficio DESC');
 	$num_oficio = $data[0]['casd01_comunicacion_invitacion']['numero_oficio'] + 1;
	return $num_oficio;
 }

 }
?>