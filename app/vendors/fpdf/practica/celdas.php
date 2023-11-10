<?php
require('../fpdf.php');

$pdf = new FPDF('L','mm','A4');
$pdf->SetTopMargin(30);
$pdf->SetLeftMArgin(20);
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->Cell(40,40,'Codigo Sectorial',1);
$pdf->Cell(40,5,'Codigo 1',1,0);
$pdf->Cell(40,5,'Codigo 2',1,2);
$pdf->Text(100,$pdf->GetY(),'El texto a imprimir'.$pdf->GetY());
$pdf->Cell(40,5,'Codigo 3',1,2);
$pdf->Cell(40,5,'Codigo 4',1,1);
$pdf->OutPut();
?>
