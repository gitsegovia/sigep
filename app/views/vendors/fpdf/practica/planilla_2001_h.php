<?php
require('../fpdf.php');

class PDF extends FPDF
{
//Cabecera de página
function Header()
{
 	$this->SetFont('Arial','I',8);
	$this->Cell(0,0,'','B',1);
}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Número de página
    $this->Cell(150,8,'Página '.$this->PageNo().'/{nb}',0,0,'R');
    $this->Cell(0,8,'FORMA: 2.001',0,0,'R');
}
}

//Recibiendo las variables



//Creación del objeto de la clase heredada
$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->SetTopMargin(20);
$pdf->SetLeftMargin(15);
$pdf->SetRightMargin(15);
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->Cell(0,7,'   OFICINA CENTRAL DE PRESUPUESTO','TLR',1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(45,7,"ENTIDAD FEDERAL :",'L',0,'R');
$pdf->Cell(0,7,$entidad,'R',1);
$pdf->SetFont('Arial','B',12);
$pdf->MultiCell(0,6,"PRESUPUESTO DE INGRESOS",'LR','C');
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,5,"(EN BOLÍVARES)",'LR',1,'C');
$pdf->Cell(30,7,"   PRESUPUESTO :",'LB');
$pdf->Cell(40,7,$presupuesto,'B');// <-- VARIABLE DE PRESUPUESTO AQUI
$pdf->Cell(0,7,"",'BR');
$pdf->Ln(9);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(80,4,"C Ó D I G O",'TLR',0,'C');
$pdf->Cell(0,4,"",'TR',1);
$pdf->Cell(80,4,"(Recursos)",'LB',0,'C');
$pdf->Cell(0,4,"",'LRB',1);
$pdf->Cell(20,10,"RAMO",'LB',0,'C');
$pdf->Cell(20,10,"GEN.",'LB',0,'C');
$pdf->Cell(20,10,"ESP.",'LB',0,'C');
$pdf->Cell(20,10,"SUB-ESP.",'LB',0,'C');
$pdf->Cell(140,10,"D E N O M I N A C I Ó N",'LB',0,'C');
$pdf->Cell(0,10,"M O N T O",'LRB',1,'C');


//*** Por aqui comienza el ciclo repetitivo para extraer los datos ***//
for($i=1; $i<=8; $i++){
$pdf->Cell(20,10,"",'LB',0,'C'); // valor de RAMO
$pdf->Cell(20,10,"",'LB',0,'C'); // valor de GEN
$pdf->Cell(20,10,"",'LB',0,'C'); // valor de ESP
$pdf->Cell(20,10,"",'LB',0,'C'); // valor de SUB-ESP
$pdf->Cell(140,10,"",'LB',0,'C');// valor de DENOMINACION
$pdf->Cell(0,10,"",'LRB',1,'C'); // valor de MONTO
}
$pdf->Cell(220,8,"T O T A L    ",'TLB',0,'R');
$pdf->Cell(0,8,"",'TLRB',0,'R');
$pdf->OutPut();
?>

