<?php
$fpdf = new FPDF('L','mm','Letter');
$fpdf->header();

//Creaci�n del objeto de la clase heredada

$fpdf->AliasNbPages();
$fpdf->SetTopMargin(20);
$fpdf->SetLeftMargin(10);
$fpdf->SetRightMargin(10);
$fpdf->AddPage();
$fpdf->SetFont('Arial','B',10);

$fpdf->Cell(0,7,'   OFICINA CENTRAL DE PRESUPUESTO','TLR',1);
$fpdf->SetFont('Arial','',9);
$fpdf->Cell(45,7,"ENTIDAD FEDERAL :",'L',0,'R');
$fpdf->Cell(0,7,$entidad,'R',1);
$fpdf->SetFont('Arial','B',12);
$fpdf->MultiCell(0,6,"INDICE DE CATEGORIAS PROGRAMATICAS",'LR','C');
$fpdf->SetFont('Arial','',9);
$fpdf->Cell(0,5,"",'LR',1,'C');
$fpdf->Cell(30,7,"   PRESUPUESTO :",'LB');
$fpdf->Cell(40,7,$presupuesto,'B');// <-- VARIABLE DE PRESUPUESTO AQUI
$fpdf->Cell(0,7,"",'BR');
$fpdf->Ln(9);

$fpdf->SetFont('Arial','B',7);

$fpdf->Cell(6,3,"",'TLR',0,'C');
$fpdf->Cell(6,3,"",'TR',0,'C');
$fpdf->Cell(6,3,"S",'TR',0,'C');
$fpdf->Cell(6,3,"",'TR',0,'C');
$fpdf->Cell(6,3,"",'TR',1,'C');
$varX = $fpdf->GetX();
$varY = $fpdf->GetY();

$fpdf->Cell(6,3,"",'LR',0,'C');
$fpdf->Cell(6,3,"",'R',0,'C');
$fpdf->Cell(6,3,"U",'R',0,'C');
$fpdf->Cell(6,3,"",'R',0,'C');
$fpdf->Cell(6,3,"",'R',1,'C');


$fpdf->Cell(6,3,"",'LR',0,'C');
$fpdf->Cell(6,3,"",'R',0,'C');
$fpdf->Cell(6,3,"B",'R',0,'C');
$fpdf->Cell(6,3,"",'R',0,'C');
$fpdf->Cell(6,3,"",'R',1,'C');


$fpdf->Cell(6,3,"",'LR',0,'C');
$fpdf->Cell(6,3,"",'R',0,'C');
$fpdf->Cell(6,3,"-",'R',0,'C');
$fpdf->Cell(6,3,"",'R',0,'C');
$fpdf->Cell(6,3,"A",'R',1,'C');


$fpdf->Cell(6,3,"",'LR',0,'C');
$fpdf->Cell(6,3,"P",'R',0,'C');
$fpdf->Cell(6,3,"P",'R',0,'C');
$fpdf->Cell(6,3,"P",'R',0,'C');
$fpdf->Cell(6,3,"C",'R',1,'C');


$fpdf->Cell(6,3,"",'LR',0,'C');
$fpdf->Cell(6,3,"R",'R',0,'C');
$fpdf->Cell(6,3,"R",'R',0,'C');
$fpdf->Cell(6,3,"R",'R',0,'C');
$fpdf->Cell(6,3,"T",'R',1,'C');


$fpdf->Cell(6,3,"S",'LR',0,'C');
$fpdf->Cell(6,3,"O",'R',0,'C');
$fpdf->Cell(6,3,"O",'R',0,'C');
$fpdf->Cell(6,3,"O",'R',0,'C');
$fpdf->Cell(6,3,"I",'R',1,'C');


$fpdf->Cell(6,3,"E",'LR',0,'C');
$fpdf->Cell(6,3,"G",'R',0,'C');
$fpdf->Cell(6,3,"G",'R',0,'C');
$fpdf->Cell(6,3,"Y",'R',0,'C');
$fpdf->Cell(6,3,"V",'R',1,'C');


$fpdf->Cell(6,3,"C",'LR',0,'C');
$fpdf->Cell(6,3,"R",'R',0,'C');
$fpdf->Cell(6,3,"R",'R',0,'C');
$fpdf->Cell(6,3,"E",'R',0,'C');
$fpdf->Cell(6,3,"I",'R',1,'C');


$fpdf->Cell(6,3,"T",'LR',0,'C');
$fpdf->Cell(6,3,"A",'R',0,'C');
$fpdf->Cell(6,3,"A",'R',0,'C');
$fpdf->Cell(6,3,"C",'R',0,'C');
$fpdf->Cell(6,3,"D",'R',1,'C');


$fpdf->Cell(6,3,"O",'LR',0,'C');
$fpdf->Cell(6,3,"M",'R',0,'C');
$fpdf->Cell(6,3,"M",'R',0,'C');
$fpdf->Cell(6,3,"T",'R',0,'C');
$fpdf->Cell(6,3,"A",'R',1,'C');


$fpdf->Cell(6,3,"R",'LRB',0,'C');
$fpdf->Cell(6,3,"A",'RB',0,'C');
$fpdf->Cell(6,3,"A",'RB',0,'C');
$fpdf->Cell(6,3,"O",'RB',0,'C');
$fpdf->Cell(6,3,"D",'RB',0,'C');

$varX = 40;
$varY = $varY-3;
$fpdf->SetXY($varX,$varY);
$fpdf->SetFont('Arial','B',9);
$fpdf->Cell(100,36,"D E N O M I N A C I O N",'TLRB',0,'C');
$fpdf->Cell(70,36,"UNIDAD EJECUTORA",'TLRB',0,'C');
$fpdf->Cell(0,36,"FUNCIONARIO RESPONSABLE",'TLRB',1,'C');


	    if($c_sector!=0){
    	foreach($sector as $sec){

		    $fpdf->SetFont('Arial','',7);
		    $fsec=$sec['cfpd02_sector']['cod_sector'];
			$fpdf->Cell(6,15,$sisap->AddCero($fsec),'LRB',0,'C');//sector
			$fpdf->Cell(6,15,"00",'RB',0,'C');//programa
			$fpdf->Cell(6,15,"00",'RB',0,'C');//sub_programa
			$fpdf->Cell(6,15,"00",'RB',0,'C');//proyecto
			$fpdf->Cell(6,15,"00",'RB',0,'C');//actividad u obra
			$varX = $fpdf->GetX();
			$varY = $fpdf->GetY();
			$fpdf->Cell(100,2,"",'T',2,'C');
			$fpdf->MultiCell(100,3,$sec['cfpd02_sector']['denominacion'],'','J');//denominacion
			$varX = $varX+100;//le sumo a X 100 del Cell debido a que lo capture antes.
			$fpdf->SetXY($varX,$varY);// cargo XY
			$fpdf->SetFont('Arial','',7);
			$fpdf->Cell(70,15,$sec['cfpd02_sector']['unidad_ejecutora'],'TLRB',0,'C');//unidad ejecutora
			$fpdf->Cell(0,15,$sec['cfpd02_sector']['funcionario_responsable'],'TLRB',1,'C');//funcionario responsable
			$varY2 = $fpdf->GetY();
		    if($c_programa!=0){
		    	foreach($programa as $pro){
		    		$fsec2=$pro['cfpd02_programa']['cod_sector'];
		    		$fprog=$pro['cfpd02_programa']['cod_programa'];
		    		if($fsec==$fsec2){//#1
				    		$fpdf->SetFont('Arial','',7);
							$fpdf->Cell(6,15,$sisap->AddCero($fsec),'LRB',0,'C');//sector
							$fpdf->Cell(6,15,$sisao->AddCero($fprog),'RB',0,'C');//programa
							$fpdf->Cell(6,15,"00",'RB',0,'C');//sub_programa
							$fpdf->Cell(6,15,"00",'RB',0,'C');//proyecto
							$fpdf->Cell(6,15,"00",'RB',0,'C');//actividad u obra
							$varX = $fpdf->GetX();
							$varY = $fpdf->GetY();
							$fpdf->Cell(100,2,"",'T',2,'C');
							$fpdf->MultiCell(100,3,$pro['cfpd02_programa']['denominacion'],'','J');//denominacion
							$varX = $varX+100;//le sumo a X 100 del Cell debido a que lo capture antes.
							$fpdf->SetXY($varX,$varY);// cargo XY
							$fpdf->SetFont('Arial','',7);
							$fpdf->Cell(70,15,$pro['cfpd02_programa']['unidad_ejecutora'],'TLRB',0,'C');//unidad ejecutora
							$fpdf->Cell(0,15,$pro['cfpd02_programa']['funcionario_responsable'],'TLRB',1,'C');//funcionario responsable
							$varY2 = $fpdf->GetY();
                            if($c_subprograma!=0){
		                    	foreach($subprograma as $sub){
		                    		$fsec3=$sub['cfpd02_sub_prog']['cod_sector'];
		                    		$fprog2=$sub['cfpd02_sub_prog']['cod_programa'];
		                    		$fsubp=$sub['cfpd02_sub_prog']['cod_sub_prog'];
		                    		if($fsec==$fsec3 && $fprog==$fprog2){//#2
							    		$fpdf->SetFont('Arial','',7);
										$fpdf->Cell(6,15,$sisap->AddCero($fsec),'LRB',0,'C');//sector
										$fpdf->Cell(6,15,$sisao->AddCero($fprog),'RB',0,'C');//programa
										$fpdf->Cell(6,15,$sisap->AddCero($fsubp),'RB',0,'C');//sub_programa
										$fpdf->Cell(6,15,"00",'RB',0,'C');//proyecto
										$fpdf->Cell(6,15,"00",'RB',0,'C');//actividad u obra
										$varX = $fpdf->GetX();
										$varY = $fpdf->GetY();
										$fpdf->Cell(100,2,"",'T',2,'C');
										$fpdf->MultiCell(100,3,$sub['cfpd02_sub_prog']['denominacion'],'','J');//denominacion
										$varX = $varX+100;//le sumo a X 100 del Cell debido a que lo capture antes.
										$fpdf->SetXY($varX,$varY);// cargo XY
										$fpdf->SetFont('Arial','',7);
										$fpdf->Cell(70,15,$sub['cfpd02_sub_prog']['unidad_ejecutora'],'TLRB',0,'C');//unidad ejecutora
										$fpdf->Cell(0,15,$sub['cfpd02_sub_prog']['funcionario_responsable'],'TLRB',1,'C');//funcionario responsable
										$varY2 = $fpdf->GetY();
			                            if($c_proyecto!=0){
			                                foreach($proyecto as $proy){
			                                	$fsec4=$proy['cfpd02_proyecto']['cod_sector'];
			                                	$fprog3=$proy['cfpd02_proyecto']['cod_programa'];
			                                	$fsubp2=$proy['cfpd02_proyecto']['cod_sub_prog'];
			                                	$fproy=$proy['cfpd02_proyecto']['cod_proyecto'];
			                                	if($fsec==$fsec4 && $fprog2==$fprog3 && $fsubp==$fsubp2){//#3
										    		$fpdf->SetFont('Arial','',7);
													$fpdf->Cell(6,15,$sisap->AddCero($fsec),'LRB',0,'C');//sector
													$fpdf->Cell(6,15,$sisao->AddCero($fprog),'RB',0,'C');//programa
													$fpdf->Cell(6,15,$sisap->AddCero($fsubp),'RB',0,'C');//sub_programa
													$fpdf->Cell(6,15,$sisap->AddCero($fproy),'RB',0,'C');//proyecto
													$fpdf->Cell(6,15,"00",'RB',0,'C');//actividad u obra
													$varX = $fpdf->GetX();
													$varY = $fpdf->GetY();
													$fpdf->Cell(100,2,"",'T',2,'C');
													$fpdf->MultiCell(100,3,$proy['cfpd02_proyecto']['denominacion'],'','J');//denominacion
													$varX = $varX+100;//le sumo a X 100 del Cell debido a que lo capture antes.
													$fpdf->SetXY($varX,$varY);// cargo XY
													$fpdf->SetFont('Arial','',7);
													$fpdf->Cell(70,15,$proy['cfpd02_proyecto']['unidad_ejecutora'],'TLRB',0,'C');//unidad ejecutora
													$fpdf->Cell(0,15,$proy['cfpd02_proyecto']['funcionario_responsable'],'TLRB',1,'C');//funcionario responsable
													$varY2 = $fpdf->GetY();
				                                	 if($c_actividad!=0){
				                                    	foreach($actividad as $act){
				                                    		$fsec5=$act['cfpd02_activ_obra']['cod_sector'];
						                                	$fprog4=$act['cfpd02_activ_obra']['cod_programa'];
						                                	$fsubp3=$act['cfpd02_activ_obra']['cod_sub_prog'];
						                                	$fproy2=$act['cfpd02_activ_obra']['cod_proyecto'];
						                                	$fact=$act['cfpd02_activ_obra']['cod_activ_obra'];
				                                    		if($fsec==$fsec5 && $fprog3==$fprog4 && $fsubp2==$fsubp3 && $fproy==$fproy2){//#4
                                                                    $fpdf->SetFont('Arial','',7);
																	$fpdf->Cell(6,15,$sisap->AddCero($fsec),'LRB',0,'C');//sector
																	$fpdf->Cell(6,15,$sisao->AddCero($fprog),'RB',0,'C');//programa
																	$fpdf->Cell(6,15,$sisap->AddCero($fsubp),'RB',0,'C');//sub_programa
																	$fpdf->Cell(6,15,$sisap->AddCero($fproy),'RB',0,'C');//proyecto
																	$fpdf->Cell(6,15,$sisap->AddCero($fact),'RB',0,'C');//actividad u obra
																	$varX = $fpdf->GetX();
																	$varY = $fpdf->GetY();
																	$fpdf->Cell(100,2,"",'T',2,'C');
																	$fpdf->MultiCell(100,3,$act['cfpd02_activ_obra']['denominacion'],'','J');//denominacion
																	$varX = $varX+100;//le sumo a X 100 del Cell debido a que lo capture antes.
																	$fpdf->SetXY($varX,$varY);// cargo XY
																	$fpdf->SetFont('Arial','',7);
																	$fpdf->Cell(70,15,$act['cfpd02_activ_obra']['unidad_ejecutora'],'TLRB',0,'C');//unidad ejecutora
																	$fpdf->Cell(0,15,$act['cfpd02_activ_obra']['funcionario_responsable'],'TLRB',1,'C');//funcionario responsable
																	$varY2 = $fpdf->GetY();
				                                    		}//#4
				                                    	}
				                                    }//act
			                                }//#3
			                                }
						                }//proy
		                    	  }//#2
		                    	}
		                    }//sub prog
				    	}//#1
		    	}
		    }//prog
    	}
    }//sector











$fpdf->Cell(0,1,"",'T',1);
$fpdf->OutPut('Indice_Categoria_Programatica'.date("d-m-Y"),'D');
?>

