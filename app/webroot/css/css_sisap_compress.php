<?php
if(extension_loaded('zlib')){
   ob_start('ob_gzhandler');
}
header ("content-type: text/css; charset: UTF-8");
header ("cache-control: must-revalidate");
$offset = 60 * 60;
$expire = "expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT";
header ($expire);
ob_start("compress");
function compress($buffer) {
   // remove comments
  $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
  $buffer = str_replace('  ', ' ', $buffer);
  $buffer = str_replace('	', ' ', $buffer);
  return $buffer;
}
 // list CSS files to be included
include('./footer/main_002.css');
include('./footer/prototip.css');
include('./themes/alphacube.css');
include('./tableorderer.css');
include('./sisap.css');
include('./menu.css');
include('./tab-view.css');
include('./dhtmlgoodies_calendar.css');
include('./inter_ventanas.css');
include('./botones_navegacion.css');
include('./lighting.css');
include('./vista_basic.css');
include('./default.css');
include('./mac_os_x.css');

if(extension_loaded('zlib')){
ob_end_flush();
}
?>
