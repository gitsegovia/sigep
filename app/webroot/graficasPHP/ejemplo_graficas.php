<?php
/*
Para el Estilo Porcentaje anexarle al .php
- Espacio que se desea graficar (de 1 a 100%)
?fil=72
?fil=100
?fil=34
?fil=80

Parámetros adicionales:
- Color de fondo:
&bkg=FFFFFF
- Ancho:
&wdt=30

Quedando de la siguiente forma :
graphporcentaje.php?fil=33&wdt=30
----------------------------------------------------------------
Para el Estilo Barras anexarle al .php 1 parámetro mínimo
- Datos:
&dat=34,69,300,2344

Parámetros adicionales:
- Color de fondo:
&bkg=FFFFFF
- Titulo
&ttl=Porcentajes+del+mes
&ttl=Valores+del+mes+pasado
&ttl=Accesos+del+día+de+hoy

Quedando de la siguiente forma :
graphbarras.php?dat=34,90,74&bkg=FFFFFF&ttl=Mi+grafica
----------------------------------------------------------------
Para el Estilo Pastel anexarle al .php 1 parámetro mínimo
- Datos:
&dat=2,5,1,6,3,4

Parámetros adicionales
- Color de fondo:
&bkg=FFFFFF
- Ancho:
&wdt=200
- Alto:
&hgt=100

Quedando de la siguiente forma :
graphpastel.php?dat=2,5,1,6,3,4&bkg=FFFFFF&wdt=200&hgt=100
---------------------------------------------------------------
Prueba de iconos para cuadros de referencias...
<img src="graphref.php?ref=11&typ=1&dim=20&bkg=EEEEEE">
<img src="graphref.php?ref=5&typ=2&dim=20&bkg=EEEEEE">  

*/
?>
<html>
<body>
<h1>Estilo de Porcentaje</h1>
<img src="http://localhost/graficasPHP/graphporcentaje.php?fil=46&wdt=30">
<h1>Barra de Barras</h1>
<img src="http://localhost/graficasPHP/graphbarras.php?dat=34.5,90,74&bkg=FFFFFF&ttl=Mi+grafica">
<img src="http://localhost/graficasPHP/graphref.php?ref=5&typ=1&dim=20&bkg=EEEEEE">
<img src="http://localhost/graficasPHP/graphref.php?ref=8&typ=1&dim=20&bkg=EEEEEE">
<img src="http://localhost/graficasPHP/graphref.php?ref=11&typ=1&dim=20&bkg=EEEEEE">
<h1>Barra de Pastel</h1>
<img src="http://localhost/graficasPHP/graphpastel.php?dat=2,5,1,6,3,4&bkg=FFFFFF&wdt=200&hgt=100">
<img src="http://localhost/graficasPHP/graphref.php?ref=5&typ=2&dim=20&bkg=EEEEEE">
<img src="http://localhost/graficasPHP/graphref.php?ref=8&typ=2&dim=20&bkg=EEEEEE">
<img src="http://localhost/graficasPHP/graphref.php?ref=11&typ=2&dim=20&bkg=EEEEEE">
<img src="http://localhost/graficasPHP/graphref.php?ref=14&typ=2&dim=20&bkg=EEEEEE">
<img src="http://localhost/graficasPHP/graphref.php?ref=17&typ=2&dim=20&bkg=EEEEEE">
<img src="http://localhost/graficasPHP/graphref.php?ref=20&typ=2&dim=20&bkg=EEEEEE">
</body>
</html>
