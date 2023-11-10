<?
// Include de la Libreria TCPDF
require_once('config/lang/eng.php');
require_once('tcpdf.php');
// Incluimos la Clase class.crearpdf.php
include('class.crearpdf.php');

// Usamos La Clase class.crearpdf.php
$pdf = new crearpdf("jeje.pdf",1);
$pdf->texto('<font color="#0CAA0C"><h1><strong>Nuestro Segundo Ejemplo de PDF<br /></strong></h1></font>

Ahora utilizamos la Simple Clase que hemos creado para realizar nuestro PDF de forma mas simple y podamos hacerlo desde cualquier parte de un Script.<br /><br />

Recordemos que estamos Usando la Libreria/Clase <font color="#0033CC"><strong>TCPDF</strong></font> basada en <font color="#0CAA0C"><strong>FPDF</strong></font> con la <font color="#EF9E1F"><strong>Simple Clase</strong></font> , en nuestro Articulo en la <strong><i>Comunidad</i> <font color="#06AAEE">DeeRme</font></strong> Podemos Usar Distintos Tamaños de Letra, Colores y hasta Imagenes, eso si usando <font color="#DD01C6">XHTML</font> valido.
<br><br>
<img src="http://www.deerme.org/images/header2.jpg">');
?>