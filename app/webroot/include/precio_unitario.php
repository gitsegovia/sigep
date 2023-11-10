<?php

function Formato($price) {
   
    $price = str_replace(".", "", $price);
    
    $price = preg_replace("/[^0-9\.]/", "", str_replace(',', '.', $price));
    $price = preg_replace("/[^0-9\.]/", "", $price);

   $price=number_format($price, 3,".","");
    
    $ultimo = strlen($price);
    $dato = substr($price, $ultimo - 1, 1);

    
    if ($dato == 0) {
        return number_format($price, 2, ',', '.');
    } else {
        return number_format($price, 3, ',', '.');
    }
}

if (isset($_GET['monto']) && !empty($_GET['monto'])) {
    $monto = trim($_GET['monto']);
    $monto = Formato($monto);
    echo trim($monto);
} else {
    //echo "0,00";
}
?>