<?php
require('../fpdf.php');

class PDF extends FPDF
{
//Cabecera de página
function Header()
{

}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Número de página
    $this->Cell(150,8,'Page '.$this->PageNo().'/{nb}',0,0,'R');
    $this->Cell(0,8,'FORMA: 2.017',0,0,'R');
}
}

//Recibiendo las variables
$entidad=strtoupper($_POST['entfederal']);
$presupuesto=strtoupper($_POST['presupuesto']);
$codigo=strtoupper($_POST['codigo']);
$sector=strtoupper($_POST['sector']);
$programa=strtoupper($_POST['programa']);


//Creación del objeto de la clase heredada
$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->SetTopMargin(30);
$pdf->SetLeftMArgin(10);
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,7,'   OFICINA CENTRAL DE PRESUPUESTO','TLR',1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(45,7,"ENTIDAD FEDERAL :",'L',0,'R');
$pdf->Cell(0,7,$entidad,'R',1);
$pdf->SetFont('Arial','B',12);
$pdf->MultiCell(0,6,"CRÉDITOS PRESUPUESTARIOS DEL PROGRAMA\nA NIVEL DE PARTIDAS",'LR','C');

$pdf->SetFont('Arial','',9);
$pdf->Cell(0,5,"(EN BOLIVARES)",'LR',1,'C');
$pdf->Cell(30,7,"   PRESUPUESTO :",'LB');
$pdf->Cell(40,7,$presupuesto,'B');
$pdf->Cell(0,7,"",'BR');
$pdf->Ln(9);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,5,"CÓDIGO:",'TLR',0,'C');
$pdf->Cell(40,5,$codigo,1,0,'C');
$pdf->Cell(0,5,"DENOMINACIÓN",1,0,'C');
$pdf->Ln();
$pdf->Cell(30,5,"SECTOR:",'LR',0,'C');
$pdf->Cell(40,5,$sector,1,0,'C');
$pdf->Cell(0,5,"",1,0,'C');
$pdf->Ln();
$pdf->Cell(30,5,"PROGRAMA:",'LRB',0,'C');
$pdf->Cell(40,5,$programa,1,0,'C');
$pdf->Cell(0,5,"",1,0,'C');
$pdf->Ln(7);
$pdf->Cell(30,5,"PARTIDA",'TLR',0,'C');
$pdf->Cell(50,5,"DENOMINACIÓN",'TLR',0,'C');
$pdf->Cell(0,5,"ASIGNACION",'1',0,'C');
$pdf->Ln();

$pdf->Cell(30,10,"",'LRB',0,'C');
$pdf->Cell(50,10,"",'LRB',0,'C');
$pdf->Cell(30,10,"ORDINARIO",'1',0,'C');
$pdf->Cell(30,10,"COORDINADO",'1',0,'C');
$pdf->Cell(30,10,"LAEE",'1',0,'C');
$pdf->Cell(30,10,"FIDES",'1',0,'C');
$pdf->Cell(30,10,"INGRESOS",'1',0,'C');
$pdf->Cell(0,10,"TOTAL",'1',0,'C');
$pdf->Ln();

for($i=0; $i<10; $i++){
	$pdf->Cell(30,7,"",'LR',0,'C');
	$pdf->Cell(50,7,"",'LR',0,'C');
	$pdf->Cell(30,7,"-- -- -- -- -- --",'LR',0,'C');
	$pdf->Cell(30,7,"-- -- -- -- -- --",'LR',0,'C');
	$pdf->Cell(30,7,"-- -- -- -- -- --",'LR',0,'C');
	$pdf->Cell(30,7,"-- -- -- -- -- --",'LR',0,'C');
	$pdf->Cell(30,7,"-- -- -- -- -- --",'LR',0,'C');
	$pdf->Cell(0,7,"-- -- -- -- -- --",'LR',1,'C');
}
$pdf->Cell(110,8,"TOTAL",'1',0,'C');
$pdf->Cell(30,8,"",'1',0,'C');
$pdf->Cell(30,8,"",'1',0,'C');
$pdf->Cell(30,8,"",'1',0,'C');
$pdf->Cell(30,8,"",'1',0,'C');
$pdf->Cell(0,8,"",'1',0,'C');
$pdf->OutPut();
?>

