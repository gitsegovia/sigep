<?php
/*
 * Creado el  31/10/2007 a las 09:39:02 AM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 */
 class Cnmd06_religiones extends AppModel {

 	var $name = "cnmd06_religiones";
 	var $useTable = "cnmd06_religiones";
 	var $primaryKey = "cod_religion";

 	var $validate = array(
 		'denominacion' => VALID_NOT_EMPTY
 	);
 }
?>