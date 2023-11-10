<?php
require('../fpdf.php');

$nom=$_POST['nombre'];
$ape=$_POST['apellido'];

$pdf = new FPDF('L','mm','A4');
$pdf->SetTopMargin(30);
$pdf->SetLeftMArgin(20);
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->Cell(40,10,$nom,1);
$pdf->Cell(40,10,$ape,1,0,'C');
$pdf->Cell(40,10,'Texto3',1);
$pdf->Cell(40,10,'Texto4',1,1);
$pdf->Cell(50,8,'Texto5',1);
$pdf->Cell(50,8,'Texto6',1);
$pdf->Cell(50,8,'Texto7',1);

$pdf->Ln(20);
$pdf->SetFont('Arial','', 10);
for($i=0; $i<20; $i++){
	$pdf->Cell(0,7,'Estamos en la hermosa linea numero:'.$i,1,1);
}
$pdf->Cell(0,5,$pdf->PageNo(),0,0,'C');
$pdf->OutPut();
?>
