<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
 class cfpd02_sector extends AppModel{
	var $name = 'cfpd02_sector';
	var $useTable = 'cfpd02_sector';
	var $primaryKey = 'cod_sector';

	var $validate = array('cod_sector'=>VALID_NOT_EMPTY,
	                                     'cod_sector'=>VALID_NUMBER,
										 'unidad_ejecutora'=>VALID_NOT_EMPTY,
										 'denominacion'=>VALID_NOT_EMPTY,

										 );

}


?>
