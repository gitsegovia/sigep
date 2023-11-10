<?php
function Formato($price) {
    $price = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$price));
    $price = preg_replace("/[^0-9\.]/", "", $price);
    return number_format($price,6,',','');
}
if(isset($_GET['monto']) && !empty($_GET['monto'])){
	$monto=trim($_GET['monto']);
	$monto=Formato($monto);
    echo trim($monto);

}else{
	//echo "0,00";
}
?>