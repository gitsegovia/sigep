<?php
/*
 * Creado el 07/11/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 11:33:03 AM
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
      //setlocale(LC_MONETARY,'es_VE');
      //$monto_nuevo = money_format('%.2n', $monto);
      //$monto_nuevo = str_replace("Bs.","", $monto_nuevo);
      $monto_nuevo= number_format($monto,2,",",".");
      echo trim($monto_nuevo);
}else{
	echo "%";
}
if(isset($_GET['precio']) && !empty($_GET['precio'])){
	$precio=$_GET['precio'];
	echo "<br><hr>";
	echo "Precio: ".FormatoPrecio($precio);
}
?>
