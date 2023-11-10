<?php
/*
 * Creado el 04/01/2008
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 01:40:15 AM
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
    if(strlen($price)>2){ $price = $price[0].$price[1];  }
    return number_format($price.$sents,2,'.','');
}

if(isset($_GET['monto']) && !empty($_GET['monto'])){
	$monto=trim($_GET['monto']);
	$monto = substr($monto, 0, -1);  // returns "abcde"

	$c_var = substr_count (strtoupper($monto), 'X');
	if($c_var==0){
	}else{
        $monto = substr($monto, 0, -7);
	}


	if($monto{0}!=','){
		echo FormatoPrecio($monto)." x 1000";
	}else{
		echo "0".FormatoPrecio($monto)." x 1000";
	}


}else{
	echo "0,00";
}

?>
