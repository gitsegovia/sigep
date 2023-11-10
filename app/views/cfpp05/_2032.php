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
    $this->Cell(130,8,'Página '.$this->PageNo().'/{nb}',0,0,'R');
    $this->Cell(0,8,'Forma: 2.032',0,0,'R');
}
}//fin de la clase

//Creación del objeto de la clase heredada
$fpdf = new PDF('L','mm','Letter');


//Recibiendo las variables aqui
$mayor="2";

if($mayor=="0"){

$fpdf->AliasNbPages();
$fpdf->SetTopMargin(20);
$fpdf->SetLeftMargin(5);
$fpdf->SetRightMargin(5);
$fpdf->AddPage();
$fpdf->SetFont('Arial','B',10);

$fpdf->Cell(0,7,'   OFICINA CENTRAL DE PRESUPUESTO','TLR',1);
$fpdf->SetFont('Arial','',9);
$fpdf->Cell(45,7,"ENTIDAD FEDERAL :",'L',0,'R');
$fpdf->Cell(0,7,"",'R',1);
$fpdf->SetFont('Arial','B',12);
$fpdf->MultiCell(0,6,"PRESUPUESTOS DE GASTOS DE LA ENTIDAD FEDERAL POR SECTORES A NIVEL DE\n PARTIDAS Y SUB-PARTIDAS",'LR','C');
$fpdf->SetFont('Arial','',9);
$fpdf->Cell(0,5,"",'LR',1,'C');
$fpdf->Cell(30,7,"   PRESUPUESTO :",'LB');
$fpdf->Cell(40,7,"",'B');// <-- VARIABLE DE PRESUPUESTO AQUI
$fpdf->Cell(0,7,"",'BR');
$fpdf->Ln(10);

$fpdf->SetFont('Arial','B',7);
$fpdf->Cell(33,4,"CÓDIGOS",'TRBL',0,'C');
$fpdf->Cell(58,4,"",'TR',0,'C');
$fpdf->Cell(30,4,"TOTAL",'TR',0,'C');
$fpdf->Cell(120,4,"S E C T O R E S",'TRB',0,'C');
$fpdf->Cell(0,4,"TOTAL",'TR',1,'C');

$fpdf->Cell(9,4,"",'RL',0,'C');
$fpdf->Cell(24,4,"SUB - PARTIDAS",'TRBL',0,'C');
$fpdf->Cell(58,4,"",'R',0,'C');
$fpdf->Cell(30,4,"PRESUP. AÑO ANT.",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(0,4,"PRESUP. AÑO PROG.",'R',1,'C');

$fpdf->Cell(9,5,"PART.",'RL',0,'C');
$fpdf->Cell(8,5,"GEN.",'TR',0,'C');
$fpdf->Cell(8,5,"ESP.",'TR',0,'C');
$fpdf->Cell(8,5,"SUB",'TR',0,'C');
$fpdf->SetFont('Arial','B',8);
$fpdf->Cell(58,5,"D E N O M I N A C I Ó N",'R',0,'C');
$fpdf->SetFont('Arial','B',9);
$fpdf->Cell(30,5,"",'R',0,'C');
$fpdf->Cell(24,5,"51",'R',0,'C');
$fpdf->Cell(24,5,"52",'R',0,'C');
$fpdf->Cell(24,5,"53",'R',0,'C');
$fpdf->Cell(24,5,"54",'R',0,'C');
$fpdf->Cell(24,5,"55",'R',0,'C');
$fpdf->Cell(0,5,"",'R',1,'C');

$fpdf->SetFont('Arial','B',7);
$fpdf->Cell(9,5,"",'RLB',0,'C');
$fpdf->Cell(8,5,"",'RB',0,'C');
$fpdf->Cell(8,5,"",'RB',0,'C');
$fpdf->Cell(8,5,"ESP.",'RB',0,'C');
$fpdf->Cell(58,5,"",'RB',0,'C');
$fpdf->Cell(30,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(0,5,"",'RB',1,'C');
$fpdf->SetFont('Arial','',7);
for($i=0; $i<10; $i++){
    $fpdf->Cell(9,15,"",'RLB',0,'C');
    $fpdf->Cell(8,15,"",'RB',0,'C');
    $fpdf->Cell(8,15,"",'RB',0,'C');
    $fpdf->Cell(8,15,"000",'RB',0,'C');
    $varX = $fpdf->GetX();//asigno X
    $varY = $fpdf->GetY();//asigno Y
    $fpdf->Cell(58,3,"",'T',2,'C');
    $fpdf->MultiCell(58,4,"AAAAAAAAAAAAAAAAAAJJJJJJJJJJJCCCC
    SAFASFJSFFCCCCCCCCCCC",'','J');
    $varX = $varX+58;//le sumo a X 58 del Cell debido a que lo capture antes.
    $fpdf->SetXY($varX,$varY);// cargo XY

    $fpdf->Cell(30,15,"43.344.343.433.333",'LRB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(0,15,"43.344.343.433.333",'RB',1,'C');
}// FIN DEL FOR
$fpdf->SetFont('Arial','B',9);
$fpdf->Cell(91,8,"T O T A L E S        ",'TRBL',0,'R');
$fpdf->SetFont('Arial','',7);
$fpdf->Cell(30,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(0,8,"",'TRB',1,'R');
$fpdf->OutPut();

}//-----------------------------------------------------------------------------------------------------------------------------------------------------





//****************** AHORA CUANDO SON MAS DE 5 SECTORES Y MENOS DE 10
if($mayor=="1"){

$fpdf->AliasNbPages();
$fpdf->SetTopMargin(20);
$fpdf->SetLeftMargin(5);
$fpdf->SetRightMargin(5);
$fpdf->AddPage();
$fpdf->SetFont('Arial','B',10);

$fpdf->Cell(0,7,'   OFICINA CENTRAL DE PRESUPUESTO','TLR',1);
$fpdf->SetFont('Arial','',9);
$fpdf->Cell(45,7,"ENTIDAD FEDERAL :",'L',0,'R');
$fpdf->Cell(0,7,"",'R',1);
$fpdf->SetFont('Arial','B',12);
$fpdf->MultiCell(0,6,"PRESUPUESTOS DE GASTOS DE LA ENTIDAD FEDERAL POR SECTORES A NIVEL DE\n PARTIDAS Y SUB-PARTIDAS",'LR','C');
$fpdf->SetFont('Arial','',9);
$fpdf->Cell(0,5,"",'LR',1,'C');
$fpdf->Cell(30,7,"   PRESUPUESTO :",'LB');
$fpdf->Cell(40,7,"",'B');// <-- VARIABLE DE PRESUPUESTO AQUI
$fpdf->Cell(0,7,"",'BR');
$fpdf->Ln(10);

$fpdf->SetFont('Arial','B',7);
$fpdf->Cell(33,4,"CÓDIGOS",'TRBL',0,'C');
$fpdf->Cell(58,4,"",'TR',0,'C');
$fpdf->Cell(30,4,"TOTAL",'TR',0,'C');
$fpdf->Cell(0,4,"S E C T O R E S",'TRB',0,'C');

$fpdf->Cell(9,4,"",'RL',0,'C');
$fpdf->Cell(24,4,"SUB - PARTIDAS",'TRBL',0,'C');
$fpdf->Cell(58,4,"",'R',0,'C');
$fpdf->Cell(30,4,"PRESUP. AÑO ANT.",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(0,4,"",'R',1,'C');

$fpdf->Cell(9,5,"PART.",'RL',0,'C');
$fpdf->Cell(8,5,"GEN.",'TR',0,'C');
$fpdf->Cell(8,5,"ESP.",'TR',0,'C');
$fpdf->Cell(8,5,"SUB",'TR',0,'C');
$fpdf->SetFont('Arial','B',8);
$fpdf->Cell(58,5,"D E N O M I N A C I Ó N",'R',0,'C');
$fpdf->SetFont('Arial','B',9);
$fpdf->Cell(30,5,"",'R',0,'C');
$fpdf->Cell(24,5,"51",'R',0,'C');
$fpdf->Cell(24,5,"52",'R',0,'C');
$fpdf->Cell(24,5,"53",'R',0,'C');
$fpdf->Cell(24,5,"54",'R',0,'C');
$fpdf->Cell(24,5,"55",'R',0,'C');
$fpdf->Cell(0,5,"56",'R',1,'C');

$fpdf->SetFont('Arial','B',7);
$fpdf->Cell(9,5,"",'RLB',0,'C');
$fpdf->Cell(8,5,"",'RB',0,'C');
$fpdf->Cell(8,5,"",'RB',0,'C');
$fpdf->Cell(8,5,"ESP.",'RB',0,'C');
$fpdf->Cell(58,5,"",'RB',0,'C');
$fpdf->Cell(30,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(0,5,"",'RB',1,'C');

$fpdf->SetFont('Arial','',7);
for($i=0; $i<10; $i++){
    $fpdf->Cell(9,15,"",'RLB',0,'C');
    $fpdf->Cell(8,15,"",'RB',0,'C');
    $fpdf->Cell(8,15,"",'RB',0,'C');
    $fpdf->Cell(8,15,"000",'RB',0,'C');
    $varX = $fpdf->GetX();//asigno X
    $varY = $fpdf->GetY();//asigno Y
    $fpdf->Cell(58,3,"",'T',2,'C');
    $fpdf->MultiCell(58,4,"AAAAAAAAAAAAAAAAAAJJJJJJJJJJJCCCC
    SAFASFJSFFCCCCCCCCCCC",'','J');
    $varX = $varX+58;//le sumo a X 58 del Cell debido a que lo capture antes.
    $fpdf->SetXY($varX,$varY);// cargo XY

    $fpdf->Cell(30,15,"43.344.343.433.333",'LRB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(0,15,"43.344.343.433.333",'RB',1,'C');
}// FIN DEL FOR

//------------------------------AGREGO UNA NUEVA PAGINA (2)------------------------------------------------//
$fpdf->AddPage();

$fpdf->Cell(0,7,'   OFICINA CENTRAL DE PRESUPUESTO','TLR',1);
$fpdf->SetFont('Arial','',9);
$fpdf->Cell(45,7,"ENTIDAD FEDERAL :",'L',0,'R');
$fpdf->Cell(0,7,"",'R',1);
$fpdf->SetFont('Arial','B',12);
$fpdf->MultiCell(0,6,"PRESUPUESTOS DE GASTOS DE LA ENTIDAD FEDERAL POR SECTORES A NIVEL DE\n PARTIDAS Y SUB-PARTIDAS",'LR','C');
$fpdf->SetFont('Arial','',9);
$fpdf->Cell(0,5,"",'LR',1,'C');
$fpdf->Cell(30,7,"   PRESUPUESTO :",'LB');
$fpdf->Cell(40,7,"",'B');// <-- VARIABLE DE PRESUPUESTO AQUI
$fpdf->Cell(0,7,"",'BR');
$fpdf->Ln(10);

$fpdf->SetFont('Arial','B',7);
$fpdf->Cell(33,4,"CÓDIGOS",'TRBL',0,'C');
$fpdf->Cell(58,4,"",'TR',0,'C');
$fpdf->Cell(30,4,"TOTAL",'TR',0,'C');
$fpdf->Cell(120,4,"S E C T O R E S",'TRB',0,'C');
$fpdf->Cell(0,4,"TOTAL",'TR',1,'C');

$fpdf->Cell(9,4,"",'RL',0,'C');
$fpdf->Cell(24,4,"SUB - PARTIDAS",'TRBL',0,'C');
$fpdf->Cell(58,4,"",'R',0,'C');
$fpdf->Cell(30,4,"PRESUP. AÑO ANT.",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(0,4,"PRESUP. AÑO PROG.",'R',1,'C');

$fpdf->Cell(9,5,"PART.",'RL',0,'C');
$fpdf->Cell(8,5,"GEN.",'TR',0,'C');
$fpdf->Cell(8,5,"ESP.",'TR',0,'C');
$fpdf->Cell(8,5,"SUB",'TR',0,'C');
$fpdf->SetFont('Arial','B',8);
$fpdf->Cell(58,5,"D E N O M I N A C I Ó N",'R',0,'C');
$fpdf->SetFont('Arial','B',9);
$fpdf->Cell(30,5,"",'R',0,'C');
$fpdf->Cell(24,5,"57",'R',0,'C');
$fpdf->Cell(24,5,"58",'R',0,'C');
$fpdf->Cell(24,5,"59",'R',0,'C');
$fpdf->Cell(24,5,"60",'R',0,'C');
$fpdf->Cell(24,5,"61",'R',0,'C');
$fpdf->Cell(0,5,"",'R',1,'C');

$fpdf->SetFont('Arial','B',7);
$fpdf->Cell(9,5,"",'RLB',0,'C');
$fpdf->Cell(8,5,"",'RB',0,'C');
$fpdf->Cell(8,5,"",'RB',0,'C');
$fpdf->Cell(8,5,"ESP.",'RB',0,'C');
$fpdf->Cell(58,5,"",'RB',0,'C');
$fpdf->Cell(30,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(0,5,"",'RB',1,'C');
$fpdf->SetFont('Arial','',7);
for($i=0; $i<10; $i++){
    $fpdf->Cell(9,15,"",'RLB',0,'C');
    $fpdf->Cell(8,15,"",'RB',0,'C');
    $fpdf->Cell(8,15,"",'RB',0,'C');
    $fpdf->Cell(8,15,"000",'RB',0,'C');
    $varX = $fpdf->GetX();//asigno X
    $varY = $fpdf->GetY();//asigno Y
    $fpdf->Cell(58,3,"",'T',2,'C');
    $fpdf->MultiCell(58,4,"AAAAAAAAAAAAAAAAAAJJJJJJJJJJJCCCC
    SAFASFJSFFCCCCCCCCCCC",'','J');
    $varX = $varX+58;//le sumo a X 58 del Cell debido a que lo capture antes.
    $fpdf->SetXY($varX,$varY);// cargo XY

    $fpdf->Cell(30,15,"43.344.343.433.333",'LRB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(0,15,"43.344.343.433.333",'RB',1,'C');
}// FIN DEL FOR
$fpdf->SetFont('Arial','B',9);
$fpdf->Cell(91,8,"T O T A L E S        ",'TRBL',0,'R');
$fpdf->SetFont('Arial','',7);
$fpdf->Cell(30,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(0,8,"",'TRB',1,'R');
$fpdf->OutPut();
}// FIN DEL IF $mayor==1 -----------------------------//

















//************************ AHORA SI SON MAS DE 10 SECTORES LO HAGO EN OTRA PLANTILLA *********************************
if($mayor=="2"){

$fpdf->AliasNbPages();
$fpdf->SetTopMargin(20);
$fpdf->SetLeftMargin(5);
$fpdf->SetRightMargin(5);
$fpdf->AddPage();
$fpdf->SetFont('Arial','B',10);

$fpdf->Cell(0,7,'   OFICINA CENTRAL DE PRESUPUESTO','TLR',1);
$fpdf->SetFont('Arial','',9);
$fpdf->Cell(45,7,"ENTIDAD FEDERAL :",'L',0,'R');
$fpdf->Cell(0,7,"",'R',1);
$fpdf->SetFont('Arial','B',12);
$fpdf->MultiCell(0,6,"PRESUPUESTOS DE GASTOS DE LA ENTIDAD FEDERAL POR SECTORES A NIVEL DE\n PARTIDAS Y SUB-PARTIDAS",'LR','C');
$fpdf->SetFont('Arial','',9);
$fpdf->Cell(0,5,"",'LR',1,'C');
$fpdf->Cell(30,7,"   PRESUPUESTO :",'LB');
$fpdf->Cell(40,7,"",'B');// <-- VARIABLE DE PRESUPUESTO AQUI
$fpdf->Cell(0,7,"",'BR');
$fpdf->Ln(10);

$fpdf->SetFont('Arial','B',7);
$fpdf->Cell(33,4,"CÓDIGOS",'TRBL',0,'C');
$fpdf->Cell(58,4,"",'TR',0,'C');
$fpdf->Cell(30,4,"TOTAL",'TR',0,'C');
$fpdf->Cell(0,4,"S E C T O R E S",'TRB',1,'C');

$fpdf->Cell(9,4,"",'RL',0,'C');
$fpdf->Cell(24,4,"SUB - PARTIDAS",'TRBL',0,'C');
$fpdf->Cell(58,4,"",'R',0,'C');
$fpdf->Cell(30,4,"PRESUP. AÑO ANT.",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(0,4,"",'R',1,'C');

$fpdf->Cell(9,5,"PART.",'RL',0,'C');
$fpdf->Cell(8,5,"GEN.",'TR',0,'C');
$fpdf->Cell(8,5,"ESP.",'TR',0,'C');
$fpdf->Cell(8,5,"SUB",'TR',0,'C');
$fpdf->SetFont('Arial','B',8);
$fpdf->Cell(58,5,"D E N O M I N A C I Ó N",'R',0,'C');
$fpdf->SetFont('Arial','B',9);
$fpdf->Cell(30,5,"",'R',0,'C');
$fpdf->Cell(24,5,"01",'R',0,'C');
$fpdf->Cell(24,5,"02",'R',0,'C');
$fpdf->Cell(24,5,"03",'R',0,'C');
$fpdf->Cell(24,5,"04",'R',0,'C');
$fpdf->Cell(24,5,"05",'R',0,'C');
$fpdf->Cell(0,5,"06",'R',1,'C');

$fpdf->SetFont('Arial','B',7);
$fpdf->Cell(9,5,"",'RLB',0,'C');
$fpdf->Cell(8,5,"",'RB',0,'C');
$fpdf->Cell(8,5,"",'RB',0,'C');
$fpdf->Cell(8,5,"ESP.",'RB',0,'C');
$fpdf->Cell(58,5,"",'RB',0,'C');
$fpdf->Cell(30,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(0,5,"",'RB',1,'C');

$fpdf->SetFont('Arial','',7);
for($i=0; $i<10; $i++){
    $fpdf->Cell(9,15,"",'RLB',0,'C');
    $fpdf->Cell(8,15,"",'RB',0,'C');
    $fpdf->Cell(8,15,"",'RB',0,'C');
    $fpdf->Cell(8,15,"000",'RB',0,'C');
	    $varX = $fpdf->GetX();//asigno X
	    $varY = $fpdf->GetY();//asigno Y
	    $fpdf->Cell(58,3,"",'T',2,'C');
	    $fpdf->MultiCell(58,4,"AAAAAAAAAAAAAAAAAAJJJJJJJJJJJCCCC
	    SAFASFJSFFCCCCCCCCCCC",'','J');
	    $varX = $varX+58;//le sumo a X 58 del Cell debido a que lo capture antes.
	    $fpdf->SetXY($varX,$varY);// cargo XY

    $fpdf->Cell(30,15,"43.344.343.433.333",'LRB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(0,15,"43.344.343.433.333",'RB',1,'C');
}// FIN DEL FOR

//-------------------------------------AGREGO UNA NUEVA PAGINA (2)------------------------------------------------//
$fpdf->AddPage();

$fpdf->Cell(0,7,'   OFICINA CENTRAL DE PRESUPUESTO','TLR',1);
$fpdf->SetFont('Arial','',9);
$fpdf->Cell(45,7,"ENTIDAD FEDERAL :",'L',0,'R');
$fpdf->Cell(0,7,"",'R',1);
$fpdf->SetFont('Arial','B',12);
$fpdf->MultiCell(0,6,"PRESUPUESTOS DE GASTOS DE LA ENTIDAD FEDERAL POR SECTORES A NIVEL DE\n PARTIDAS Y SUB-PARTIDAS",'LR','C');
$fpdf->SetFont('Arial','',9);
$fpdf->Cell(0,5,"",'LR',1,'C');
$fpdf->Cell(30,7,"   PRESUPUESTO :",'LB');
$fpdf->Cell(40,7,"",'B');// <-- VARIABLE DE PRESUPUESTO AQUI
$fpdf->Cell(0,7,"",'BR');
$fpdf->Ln(10);

$fpdf->SetFont('Arial','B',7);
$fpdf->Cell(33,4,"CÓDIGOS",'TRBL',0,'C');
$fpdf->Cell(58,4,"",'TR',0,'C');
$fpdf->Cell(30,4,"TOTAL",'TR',0,'C');
$fpdf->Cell(0,4,"S E C T O R E S",'TRB',1,'C');

$fpdf->Cell(9,4,"",'RL',0,'C');
$fpdf->Cell(24,4,"SUB - PARTIDAS",'TRBL',0,'C');
$fpdf->Cell(58,4,"",'R',0,'C');
$fpdf->Cell(30,4,"PRESUP. AÑO ANT.",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(0,4,"",'R',1,'C');

$fpdf->Cell(9,5,"PART.",'RL',0,'C');
$fpdf->Cell(8,5,"GEN.",'TR',0,'C');
$fpdf->Cell(8,5,"ESP.",'TR',0,'C');
$fpdf->Cell(8,5,"SUB",'TR',0,'C');
$fpdf->SetFont('Arial','B',8);
$fpdf->Cell(58,5,"D E N O M I N A C I Ó N",'R',0,'C');
$fpdf->SetFont('Arial','B',9);
$fpdf->Cell(30,5,"",'R',0,'C');
$fpdf->Cell(24,5,"07",'R',0,'C');
$fpdf->Cell(24,5,"08",'R',0,'C');
$fpdf->Cell(24,5,"09",'R',0,'C');
$fpdf->Cell(24,5,"10",'R',0,'C');
$fpdf->Cell(24,5,"11",'R',0,'C');
$fpdf->Cell(0,5,"12",'R',1,'C');

$fpdf->SetFont('Arial','B',7);
$fpdf->Cell(9,5,"",'RLB',0,'C');
$fpdf->Cell(8,5,"",'RB',0,'C');
$fpdf->Cell(8,5,"",'RB',0,'C');
$fpdf->Cell(8,5,"ESP.",'RB',0,'C');
$fpdf->Cell(58,5,"",'RB',0,'C');
$fpdf->Cell(30,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(0,5,"",'RB',1,'C');
$fpdf->SetFont('Arial','',7);
for($i=0; $i<10; $i++){
    $fpdf->Cell(9,15,"",'RLB',0,'C');
    $fpdf->Cell(8,15,"",'RB',0,'C');
    $fpdf->Cell(8,15,"",'RB',0,'C');
    $fpdf->Cell(8,15,"000",'RB',0,'C');
    $varX = $fpdf->GetX();//asigno X
    $varY = $fpdf->GetY();//asigno Y
    $fpdf->Cell(58,3,"",'T',2,'C');
    $fpdf->MultiCell(58,4,"AAAAAAAAAAAAAAAAAAJJJJJJJJJJJCCCC
    SAFASFJSFFCCCCCCCCCCC",'','J');
    $varX = $varX+58;//le sumo a X 58 del Cell debido a que lo capture antes.
    $fpdf->SetXY($varX,$varY);// cargo XY

    $fpdf->Cell(30,15,"43.344.343.433.333",'LRB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(0,15,"43.344.343.433.333",'RB',1,'C');
}// FIN DEL FOR


$fpdf->AddPage();

$fpdf->Cell(0,7,'   OFICINA CENTRAL DE PRESUPUESTO','TLR',1);
$fpdf->SetFont('Arial','',9);
$fpdf->Cell(45,7,"ENTIDAD FEDERAL :",'L',0,'R');
$fpdf->Cell(0,7,"",'R',1);
$fpdf->SetFont('Arial','B',12);
$fpdf->MultiCell(0,6,"PRESUPUESTOS DE GASTOS DE LA ENTIDAD FEDERAL POR SECTORES A NIVEL DE\n PARTIDAS Y SUB-PARTIDAS",'LR','C');
$fpdf->SetFont('Arial','',9);
$fpdf->Cell(0,5,"",'LR',1,'C');
$fpdf->Cell(30,7,"   PRESUPUESTO :",'LB');
$fpdf->Cell(40,7,"",'B');// <-- VARIABLE DE PRESUPUESTO AQUI
$fpdf->Cell(0,7,"",'BR');
$fpdf->Ln(10);

$fpdf->SetFont('Arial','B',7);
$fpdf->Cell(33,4,"CÓDIGOS",'TRBL',0,'C');
$fpdf->Cell(58,4,"",'TR',0,'C');
$fpdf->Cell(30,4,"TOTAL",'TR',0,'C');
$fpdf->Cell(120,4,"S E C T O R E S",'TRB',0,'C');
$fpdf->Cell(0,4,"TOTAL",'TR',1,'C');

$fpdf->Cell(9,4,"",'RL',0,'C');
$fpdf->Cell(24,4,"SUB - PARTIDAS",'TRBL',0,'C');
$fpdf->Cell(58,4,"",'R',0,'C');
$fpdf->Cell(30,4,"PRESUP. AÑO ANT.",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(24,4,"",'R',0,'C');
$fpdf->Cell(0,4,"PRESUP. AÑO PROG.",'R',1,'C');

$fpdf->Cell(9,5,"PART.",'RL',0,'C');
$fpdf->Cell(8,5,"GEN.",'TR',0,'C');
$fpdf->Cell(8,5,"ESP.",'TR',0,'C');
$fpdf->Cell(8,5,"SUB",'TR',0,'C');
$fpdf->SetFont('Arial','B',8);
$fpdf->Cell(58,5,"D E N O M I N A C I Ó N",'R',0,'C');
$fpdf->SetFont('Arial','B',9);
$fpdf->Cell(30,5,"",'R',0,'C');
$fpdf->Cell(24,5,"13",'R',0,'C');
$fpdf->Cell(24,5,"14",'R',0,'C');
$fpdf->Cell(24,5,"15",'R',0,'C');
$fpdf->Cell(24,5,"",'R',0,'C');
$fpdf->Cell(24,5,"",'R',0,'C');
$fpdf->Cell(0,5,"",'R',1,'C');

$fpdf->SetFont('Arial','B',7);
$fpdf->Cell(9,5,"",'RLB',0,'C');
$fpdf->Cell(8,5,"",'RB',0,'C');
$fpdf->Cell(8,5,"",'RB',0,'C');
$fpdf->Cell(8,5,"ESP.",'RB',0,'C');
$fpdf->Cell(58,5,"",'RB',0,'C');
$fpdf->Cell(30,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(24,5,"",'RB',0,'C');
$fpdf->Cell(0,5,"",'RB',1,'C');
$fpdf->SetFont('Arial','',7);
for($i=0; $i<10; $i++){
    $fpdf->Cell(9,15,"",'RLB',0,'C');
    $fpdf->Cell(8,15,"",'RB',0,'C');
    $fpdf->Cell(8,15,"",'RB',0,'C');
    $fpdf->Cell(8,15,"000",'RB',0,'C');
	    $varX = $fpdf->GetX();//asigno X
	    $varY = $fpdf->GetY();//asigno Y
	    $fpdf->Cell(58,3,"",'T',2,'C');
	    $fpdf->MultiCell(58,4,"AAAAAAAAAAAAAAAAAAJJJJJJJJJJJCCCSAFASFJSFFCCCCCCCCCCC",'','J');
	    $varX = $varX+58;//le sumo a X 58 del Cell debido a que lo capture antes.
	    $fpdf->SetXY($varX,$varY);// cargo XY
    $fpdf->Cell(30,15,"43.344.343.433.333",'LRB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(24,15,"",'RB',0,'C');
    $fpdf->Cell(0,15,"43.344.343.433.333",'RB',1,'C');
}




$fpdf->SetFont('Arial','B',9);
$fpdf->Cell(91,8,"T O T A L E S        ",'TRBL',0,'R');
$fpdf->SetFont('Arial','',7);
$fpdf->Cell(30,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(24,8,"",'TRB',0,'R');
$fpdf->Cell(0,8,"",'TRB',1,'R');
$fpdf->OutPut();

}

