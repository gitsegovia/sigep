<?php
require('../fpdf.php');

class PDF extends FPDF
{
//Cabecera de página
function Header()
{
$this->SetFont('Arial','I',8);
$this->Cell(0,7,'   OFICINA CENTRAL DE PRESUPUESTO','TLR',1);
$this->SetFont('Arial','',9);
$this->Cell(45,7,"ENTIDAD FEDERAL :",'L',0,'R');
$this->Cell(0,7,$entidad,'R',1);
$this->SetFont('Arial','B',12);
$this->MultiCell(0,6,"CRÉDITOS PRESUPUESTARIOS DEL PROGRAMA\nA NIVEL DE PARTIDAS",'LR','C');
$this->SetFont('Arial','',9);
$this->Cell(0,5,"(EN BOLIVARES)",'LR',1,'C');
$this->Cell(30,7,"   PRESUPUESTO :",'LB');
$this->Cell(40,7,$presupuesto,'B');
$this->Cell(0,7,"",'BR');
$this->Ln(9);
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
$pdf->OutPut();
?>