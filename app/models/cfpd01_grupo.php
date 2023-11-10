<?php
/*
 * Fecha: 15/06/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * cake
 */

 class Cfpd01_grupo extends AppModel{
   var $helpers = array('Html', 'Javascript', 'Ajax','Form');
 	var $name = "cfpd01_grupo";
	var $useTable = "cfpd01_grupo";
 	var $primaryKey = "cod_grupo";

 	var $validate = array('cod_grupo'=>VALID_NOT_EMPTY,
	                                     'cod_grupo'=>VALID_NUMBER, 
										 'concepto'=>VALID_NOT_EMPTY,
										 'descripcion'=>VALID_NOT_EMPTY,
										 
										 );





 }

?>
