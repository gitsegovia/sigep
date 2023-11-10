<?php
require '../jsmin-1.1.1.php';

// Output a minified version of example.js.
$archivo_script_js ='js_iconos_modulos.js';
$codigo_comprimido = JSMin::minify(file_get_contents($archivo_script_js));
$archivo = fopen("comprimido_js_iconos_modulos.js", "w+");
fwrite($archivo, $codigo_comprimido);
fclose($archivo);

echo "<b>LISTO 2!</b>";

?>
