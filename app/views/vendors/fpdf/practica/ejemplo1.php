<?php
require('../fpdf.php');

$pdf = new FPDF	('L','mm','A4');
$pdf->SetTopMargin(40);// margen superior de la pagina //
$pdf->SetLeftMargin(20);// margen izquierdo de la pagina //
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$var=100;
$pdf->Cell(80,20,'Hola alberto jose!!!!!',1,1,'C');
$pdf->Ln();
$pdf->Cell(40,10,'Hola alberto jose 2!!!');
$pdf->OutPut();
?>