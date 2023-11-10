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
    $this->Cell(0,8,'FORMA: 2.000',0,0,'R');
}
}

//Recibiendo las variables
$entidad=strtoupper($_POST['entfederal']);
$presupuesto=strtoupper($_POST['presupuesto']);
$codigo=strtoupper($_POST['codigo']);
$sector=strtoupper($_POST['sector']);
$programa=strtoupper($_POST['programa']);
$n=$_POST['num_registro'];


//Creación del objeto de la clase heredada
$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->SetTopMargin(20);
$pdf->SetLeftMargin(15);
$pdf->SetRightMargin(10); 
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

$pdf->Cell(0,7,'   OFICINA CENTRAL DE PRESUPUESTO','TLR',1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(45,7,"ENTIDAD FEDERAL :",'L',0,'R');
$pdf->Cell(0,7,$entidad,'R',1);
$pdf->SetFont('Arial','B',12);
$pdf->MultiCell(0,6,"IDENTIFICACIÓN DE LA ENTIDAD FEDERAL",'LR','C');
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,5,"",'LR',1,'C');
$pdf->Cell(30,7,"   PRESUPUESTO :",'LB');
$pdf->Cell(40,7,$variable_de_presupuesto,'B'); //<-----variable_de_presupuesto
$pdf->Cell(0,7,"",'BR');
$pdf->Ln(10);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,10,"  DOMICILIO LEGAL",'TLR',1);
$pdf->Cell(0,10,"",'LRB',1);
$pdf->Cell(50,6,"  CIUDAD",'LB',0);
$pdf->Cell(50,6,"  TELÉFONOS",'LB',0);
$pdf->Cell(60,6,"  DIRECCION INTERNET",'LB',0);
$pdf->Cell(50,6,"  FAX",'LB',0);
$pdf->Cell(0,6,"  CÓDIGO POSTAL",'LRB',0);
$pdf->Ln();
$pdf->Cell(50,20,"",'LR',0);
$pdf->Cell(50,20,"",'LR',0);
$pdf->Cell(60,20,"",'LR',0);
$pdf->Cell(50,20,"",'LR',0);
$pdf->Cell(0,20,"",'LR',0);
$pdf->Ln();

$pdf->Cell(0,10,"  GOBERNADOR (a)",'TLR',1);
$pdf->Cell(0,10,"",'LRB',1);
$pdf->Cell(0,10,"  CONTRALOR (a) GENERAL DEL ESTADO",'TLR',1);
$pdf->Cell(0,10,"",'LRB',1);
$pdf->Cell(0,10,"  PRESIDENTE Y/O VICEPRESIDENTE DEL CONCEJO LEGISLATIVO ESTADAL",'TLR',1);
$pdf->Cell(0,10,"",'LRB',1);
$pdf->Cell(0,10,"  DIRECTOR (a) DE PRESUPUESTO",'TLR',1);
$pdf->Cell(0,10,"",'LRB',1);
$pdf->OutPut();
?>

