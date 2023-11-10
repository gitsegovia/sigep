<?php
/*
 * Creado el 07/11/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 12:01:31 PM
 */

if(isset($_GET['monto']) && !empty($_GET['monto'])){
	$monto=trim($_GET['monto']);
	$monto = substr($monto, 0, -1);  // returns "abcde"
	//$monto=Formato($monto);
    //$monto_nuevo= number_format($monto,2,",",".");
    echo $monto." %";//trim($monto_nuevo);
}else{
	echo "0,00";
}

?>
