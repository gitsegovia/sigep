<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
 class v_cfpd02_sector extends AppModel{
	var $name = 'v_cfpd02_sector';
	var $useTable = 'v_cfpd02_sector_cfpd05';
	var $primaryKey = 'cod_sector';

	var $validate = array('cod_sector'=>VALID_NOT_EMPTY,
	                                     'cod_sector'=>VALID_NUMBER,
										 'unidad_ejecutora'=>VALID_NOT_EMPTY,
										 'denominacion'=>VALID_NOT_EMPTY,

										 );

}


?>
