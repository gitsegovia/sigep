<?php
function Formato($price) {
    $price = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$price));
    if (substr($price,-3,1)=='.') {
        $sents = '.'.substr($price,-2);
        $price = substr($price,0,strlen($price)-3);
    } elseif (substr($price,-2,1)=='.') {
        $sents = '.'.substr($price,-1);
        $price = substr($price,0,strlen($price)-2);
    } else {
        $sents = '.00';
    }
    $price = preg_replace("/[^0-9]/", "", $price);
    return number_format($price.$sents,2,'.','');
}

if(isset($_GET['monto']) && !empty($_GET['monto'])){
	$monto=trim($_GET['monto']);
	$monto2=trim($_GET['monto']);
	$monto=Formato($monto);
	if($monto2[0]=="-"){
		$monto = $monto*(-1);
	}
    $monto_nuevo= number_format($monto,2,",",".");
    echo trim($monto_nuevo);
}else{
	echo "0,00";
}
if(isset($_GET['monto2']) && !empty($_GET['monto2'])){
	$precio=$_GET['monto2'];
	echo Formato($precio);
}

?>