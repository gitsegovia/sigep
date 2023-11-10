<html>
<head><title></title>
<style type="text/css">
<!--
.Estilo1 {
	color: #FFFFFF;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
}
.Estilo3 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
.Estilo4 {font-size: 14}
.Estilo5 {font-family: Arial, Helvetica, sans-serif; font-size: 14; }
-->
</style>
</head>
<body>

<form name="f1" method="POST" action="planilla2.php">
<center>
<br><br><br><br>
<table width="500" bgcolor="#EFEFEF" border="0" cellpadding="2" cellspacing="0">
  <tr bgcolor="#003399">
    <td colspan="2"><div align="center" class="Estilo1">Tabla de Prueba </div></td>
    </tr>
  <tr>
    <td width="238" bgcolor="#F4F4F4">&nbsp;</td>
    <td width="262" bgcolor="#F8F8F8">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F4F4F4"><div align="right"><strong>Entidad&nbsp;Federal:  </strong> </div></td>
    <td bgcolor="#F8F8F8"><input type="text" name="entfederal" size="35" maxlength="40"></td>
  </tr>
  <tr>
    <td bgcolor="#F4F4F4"><div align="right"><strong>Presupuesto:</strong></div></td>
    <td bgcolor="#F8F8F8"><input type="text" name="presupuesto" size="15" maxlength="30"></td>
  </tr>
  <tr>
    <td bgcolor="#F4F4F4"><div align="right"><strong>Codigo:      </strong></div></td>
    <td bgcolor="#F8F8F8"><input type="text" name="codigo" size="15" maxlength="30"></td>
  </tr>
  <tr>
    <td bgcolor="#F4F4F4"><div align="right"><strong>Sector: </strong></div></td>
    <td bgcolor="#F8F8F8"><input type="text" name="sector" size="15" maxlength="30"></td>
  </tr>
  <tr>
    <td bgcolor="#F4F4F4"><div align="right"><strong>Programa:</strong></div></td>
    <td bgcolor="#F8F8F8"><input type="text" name="programa" size="15" maxlength="30"></td>
  </tr>
  
  <tr bgcolor="#F4F4F4">
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><div align="center">
  <input type="submit" name="envio" value="   Enviar    ">
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="reset" name="borrar" value="   Limpiar   "> 
    </div></td>
    </tr>
</table>
<p>&nbsp;</p>
<table width="500" height="68" border="0">
  <tr>
    <td><p class="Estilo3 Estilo4"><strong>Nota</strong>: En este caso cuando se crea el documento, se le ofrece la opcion al usuario para abrir o descargar el archivo, ademas se le asigna un nombre por nosotros el cual es: forma2017.pdf (Estas son opciones que ofrece el PDF) </p>      </td>
  </tr>
  <tr>
    <td><p class="Estilo5">A parte de esto se puede forzar la descarga directamente a un lugar especifico sin preguntarle al usuario. </p>      </td>
  </tr>
</table>
<p>&nbsp;</p>
</center>
</form>



</body>

</html>
