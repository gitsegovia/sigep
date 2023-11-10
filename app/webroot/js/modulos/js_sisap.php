<?php
//iniciamos el output buffer
ob_start();
//.............................
include('./Moo.js'); echo "\r\r\n";
include('./Utility.js'); echo "\r\r\n";
include('./Function.js'); echo "\r\r\n";
include('./Array.js'); echo "\r\r\n";
include('./String.js'); echo "\r\r\n";
include('./Element.js'); echo "\r\r\n";
include('./Event.js'); echo "\r\r\n";
include('./Window.js'); echo "\r\r\n";
include('./Window_002.js'); echo "\r\r\n";
include('./Common.js'); echo "\r\r\n";
include('./Dom.js'); echo "\r\r\n";
include('./Fx_008.js'); echo "\r\r\n";
include('./Fx_003.js'); echo "\r\r\n";
include('./Fx_004.js'); echo "\r\r\n";
include('./Fx_002.js'); echo "\r\r\n";
include('./Fx_005.js'); echo "\r\r\n";
include('./Fx.js'); echo "\r\r\n";

//.............................
//obtenemos el output bufer
$contenido = ob_get_contents();
//guardamos el archivo
file_put_contents("js_iconos_modulos.js",$contenido);
//volcamos por pantalla y cerramos el output buffer
ob_end_clean();
echo "<b>LISTO 1!</b>";
?>
