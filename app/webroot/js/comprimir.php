<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Compresi&oacute;n de js del sistema</title>
<style type="text/css">
body{
	margin:0;
	padding:10px;
	font-family:tahoma;
	font-size:13pt;
	background: #E6EDF5;
}
h3{
	text-align: left;
	background: #E6EDF5;
	color: #4F76A3;
	font-size: 100% !important;
	height:25px;
	padding:3px;
}
a{
	color: #4F76A3;
	font-weight: bold;
}

a:hover{
	text-decoration: none;
}


.input_txt_scr {
	font-size: 20px;
	text-transform:uppercase;
	margin-top:1px;
	border: 1px solid #7F9DB9;
	height: 30px;
	text-align: center;
	color: #940000;
	font-weight: bold;
	text-shadow: 0.05em 0.05em #e1e1e1;
}

.input_bt_scr {
	text-transform:uppercase;
	background-color:#003d4c;
	margin-top:1px;
	border: 1px solid #000000;
	height: 30px;
	text-align: center;
	cursor: pointer;
	color: #FFFFFF;
	font-weight: bold;
}


table.admin_porcentaje_barra {
	background-color: #e3e3e3;
	border: solid 1px #d5d5d5;
	width: 100%;
	padding: 10px;
	border-collapse: collapse;
}

table.admin_porcentaje_barra td {
	padding: 10px;
}

</style>

</head>

<body>


<?php

	function codigo_js_inst ($inst=1) {
		$instituciones = array(1=>'SIGEP_DEFAULT');
	    if($instituciones[$inst]=='SIGEP_DEFAULT'){
	    	$cj = 'z';//SIGEP_DEFAULT
	    }else{
	    	$cj = '*';//POR DEFECTO
	    }

	    return date("Y").$cj;
	}


if(isset($_POST["scrvalido"])){

	$cod_js = codigo_js_inst (1);
	$codigo_js = intval(rand());

	$cc1 = "3dba442aff1a71cd380ffb78d64fd4e2";
	$codigo_nat1 = strtoupper($cod_js.$codigo_js);
	$codigo_nat1 = md5($codigo_nat1);

	$cc2 = strtoupper(substr($_POST["scrvalido"], 0, 5));
	$cc2 = md5($cc2);
	$codigo_nat2 = strtoupper(substr($_POST["scrvalido"],5).$codigo_js);
	$codigo_nat2 = md5($codigo_nat2);

	if (!empty($_POST["scrvalido"]) && !empty($cod_js) && $cc1 == $cc2 && $codigo_nat1 == $codigo_nat2) {
		$scr_valido = true;
	}else if (empty($_POST["scrvalido"]) || $_POST["scrvalido"]=="" || $_POST["scrvalido"]==null){
		echo "<span style='color:#840000;'>Por favor ingrese el c&oacute;digo...</span>";
		unset($scr_valido);
	}else{
		echo "<span style='color:#840000;'>El c&oacute;digo es incorrecto...</span>";
		unset($scr_valido);
	}
}else{
	unset($scr_valido);
}
?>


<?php
	if(!isset($scr_valido)){
?>

<h3>Ingrese c&oacute;digo de autorizaci&oacute;n.</h3>
<br />

<form name="scrvalido_form" id="scrvalido_form" method = "post" action="comprimir.php">

<input type="password" name="scrvalido" value="" maxlength="32" class="input_txt_scr" />
<br /><br />

<input type="submit" name="bt_scrvalido" value="VALIDAR C&Oacute;DIGO" class="input_bt_scr" />
<br />

</form>

<?php
	}else{
?>


<div style="font-size:18px;color:cyan;border: 1px solid #a1a1a1;background-color:#003d4c;">
<?php /*echo $html->image('alert.png', array('border'=>'0'));*/ ?> &nbsp;Compresi&oacute;n de js del sistema.
<table width="100%" border="0" class="admin_porcentaje_barra">
	   <tr>
	       <td align="center" width="60">
			<img src="/img/langmanager.png" border="0" />
		   </td>
		   <td align="justify" bgcolor="white" id="b" width="800" style="border: 1px solid #a1a1a1;">
		   	<span style='color:yellow;font-size:12px;font-weight:bold;'>&nbsp;</span>
		   	<br />
		   	<span style='color:#000000;font-family:arial;font-size:16px;'>
				<img src="/img/rojo.gif" border="0" /> &nbsp;&nbsp;El archivo <i><b>script_js.thtml</b></i> no sera actualizado...<br /><br />
				<img src="/img/rojo.gif" border="0" /> &nbsp;&nbsp;Cuando se tengan que actualizar nuevos archivos js se realizará en el archivo ubicado en <i><b>sigep/app/webroot/js/js_sisap.php</b></i><br /><br />
				<img src="/img/rojo.gif" border="0" /> &nbsp;&nbsp;Para cualquier modificación que se haga a los archivos js se tiene que ejecutar los dos enlaces en orden 1 y 2<br /><br />
				<img src="/img/rojo.gif" border="0" /> &nbsp;&nbsp;Esto se debe hacer tanto en el servidor local. como en el servidor central, es decir ejecutar esta secci&oacute;n cada vez que se haga un cambio en las js<br /><br />
			</span>
		   </td>
	   </tr>
	   <tr><td colspan="2" align="center" width="380">
	    <br />
		<input type="button" value="SALIR" onclick="javascript:window.location.href='/js/comprimir.php';" />
	   </td></tr>
	</table>

	<img src="/img/log_sigep_v1_2.png" border="0" />
</div>


<h3>Enlaces para compresi&oacute;n de js del sistema:</h3>

  <a href="js_sisap.php" target="frame_js">1. Crear js</a>
  <br />
  <a href="comprimir_js.php" target="frame_js">2. Comprimir js</a>
  <br />

<?php
	}
?>


  <iframe id="frame_js" name="frame_js" style="border: 0; width: 100%; height: 350px;"></iframe>

  </body>
</html>
