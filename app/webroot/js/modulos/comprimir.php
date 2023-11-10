<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>compresi&oacute;n de js del sistema</title>
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
</style>


</head>

<body>
<h3>Compresión de js del sistema.</h3>
1.El archivo <i><b>script_js.thtml</b></i> no sera actualizado...<br/>
2.Cuando se tengan que actualizar nuevos archivos js se realizará en el archivo ubicado en <i><b>sisap/app/webroot/js/js_sisap.php</b></i><br/>
3.Para cualquier modificación que se haga a los archivos js se tiene que ejecutar los dos enlaces en orden 1 y 2<br/>
4.Esto se debe hacer tanto en el servidor local. como en el de falcon, es decir ejecutar esta seccion cada vez que se haga un cambio en las js
<br/>
  <a href="js_sisap.php" target="frame_js">1. Crear js</a>
  <br />
  <a href="comprimir_js.php" target="frame_js">2. Comprimir js</a>
  <br />

  <iframe id="frame_js" name="frame_js" style="border: 0; width: 100%; height: 350px;"></iframe>

  </body>
</html>
