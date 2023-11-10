<?php
/*
 * Creado el 07/11/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 03:34:18 PM
 */
if(isset($_GET['monto']) && !empty($_GET['monto'])){
	$valor=trim($_GET['monto']);
	$valor = substr($valor, 0, -1);  // returns "abcde"
	$total="1.".$valor;
	echo $total;
}else{
	echo "vacio";
}

?>
