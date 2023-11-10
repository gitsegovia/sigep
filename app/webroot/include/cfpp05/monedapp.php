<?php

function Formato($price) {
    $price = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$price));
    $price = preg_replace("/[^0-9\.]/", "", $price);

	if(isset($_GET['decimal']) && !empty($_GET['decimal'])){
		$decimal = (int)$_GET['decimal'];
		if($decimal < 0)
			$decimal = 2;
	}else{
		$decimal = 2;
	}

    return number_format($price,$decimal,',','');
}


function Formato9($price) {

	if(isset($_GET['decimal']) && !empty($_GET['decimal'])){
		$decimal = (int)$_GET['decimal'];
	}else{
		$decimal = 2;
	}

	$sdecimal = $decimal - 1;
    $price = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$price));
    if (substr($price,-$decimal,1)=='.') {
        $sents = '.'.substr($price,-$sdecimal);
        $price = substr($price,0,strlen($price)-$decimal);
    } elseif (substr($price,-$sdecimal,1)=='.') {
        $sents = '.'.substr($price,-1);
        $price = substr($price,0,strlen($price)-$sdecimal);
    } else {
		$cd = "";
		for($d=0;$d<$decimal;$d++){
			$cd = $cd."0";
		}
		$sents = '.'.$cd;
    }
    $price = preg_replace("/[^0-9]/", "", $price);
    return number_format($price.$sents,$decimal,'.','');
}

if(isset($_GET['monto']) && !empty($_GET['monto'])){

	$monto=trim($_GET['monto']);
	$monto=Formato($monto);
	echo trim($monto);

/*
	if(isset($_GET['decimal']) && !empty($_GET['decimal'])){
		$decimal = $_GET['decimal'];
	}else{
		$decimal = 2;
	}

	$monto=Formato9($monto);
    $monto = number_format($monto,$decimal,",",".");
    echo trim($monto);
*/

}else{
	$monto=trim($_GET['monto']);
	$monto=Formato($monto);
	echo trim($monto);

/*
	if(isset($_GET['decimal']) && !empty($_GET['decimal'])){
		$decimal = $_GET['decimal'];
	}else{
		$decimal = 2;
	}
	// $cd = mascara("", $decimal);
	$cd = "";
	for($d=0;$d<$decimal;$d++){
		$cd = $cd."0";
	}
	echo "0,".$cd;
*/
}
?>