<?php
/*
 * Creado el 07/11/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 12:01:31 PM
 */
 function FormatoPrecio($price) {
    $price = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$price));
    if (substr($price,-3,1)=='.') {
        $sents = '.'.substr($price,-2);
        $price = substr($price,0,strlen($price)-3);
    } elseif (substr($price,-2,1)=='.') {
        $sents = '.'.substr($price,-1);
        $price = substr($price,0,strlen($price)-2);
    } else {
        $sents = '.%';
    }
    $price = preg_replace("/[^0-9]/", "", $price);
    return number_format($price.$sents,2,'.','');
}

if(isset($_GET['monto']) && !empty($_GET['monto'])){
	$monto=trim($_GET['monto']);
	$monto = substr($monto, 0, -1);  // returns "abcde"

	if($monto{0}!=','){
		echo FormatoPrecio($monto)." %";
	}else{
		echo "0".FormatoPrecio($monto)." %";
	}


}else{
	echo "0,00";
}

?>
