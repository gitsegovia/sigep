<?php
/*
 * Fecha: 15/06/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * cake
 */

 class Cfpd01_ano_partida extends AppModel{

 	var $name = "cfpd01_ano_partida";
 	var $useTable = "cfpd01_ano_2_partida";
 	var $primaryKey = "cod_partida";
	
	
		var $validate = array('cod_partida'=>VALID_NOT_EMPTY,
	                                     'cod_partida'=>VALID_NUMBER, 
										 'concepto'=>VALID_NOT_EMPTY,
										 'descripcion'=>VALID_NOT_EMPTY,
										 
										 );
	

 }

?>
