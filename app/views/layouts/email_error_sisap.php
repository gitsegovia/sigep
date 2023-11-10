<?php
if(isset($_SESSION['ERROR_SISAP_WARNING']) && $_SESSION['ERROR_SISAP_WARNING']!='0'){
 	email_error_sisap ($_SESSION['ERROR_SISAP_WARNING']);
 	$_SESSION['ERROR_SISAP_WARNING'] = '0';
 	//unset($_SESSION['ERROR_SISAP_WARNING']);
 }else{
 	//echo "<h1>Falló al enviar el correo</h1><br>".$_SESSION['ERROR_SISAP_WARNING'];
 }
?>
