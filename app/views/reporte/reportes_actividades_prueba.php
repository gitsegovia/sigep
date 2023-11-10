<?php
vendor('utf8_tcpdf/tcpdf');
set_time_limit(0);

if (!defined('PARAGRAPH_STRING')) define('PARAGRAPH_STRING', '~~~');

class fpdfview extends TCPDF {

    function setup ($orientation='Landscape',$unit='mm',$format='A4') {
        $this->TCPDF($orientation, $unit, $format);
    }


    function fpdfOutput ($name = 'page.pdf', $destination = 's') {
        // I: send the file inline to the browser. The plug-in is used if available.
        //    The name given by name is used when one selects the "Save as" option on the link generating the PDF.
        // D: send to the browser and force a file download with the name given by name.
        // F: save to a local file with the name given by name.
        // S: return the document as a string. name is ignored.
        return $this->Output($name, $destination);
    }

    function Header(){
	$this->Image('/var/www/sigep/app/webroot/img/escudo_base/escudo10.jpg',7,31,19);
	$this->SetFont('vera','B',10);
	$this->Cell(28,5,"",'TL',0);
	$this->Cell(0,5,$_SESSION['entidad_federal_aux'],'TR',1);//----AQUI SE IMPRIME LA ENTIDAD QUE DEBERIA VENIR SETEADA (SET) --- JUAN
	$this->Cell(28,5,"",'L',0);
	$this->SetFont('vera','',9);
	$this->Cell(0,5,$_SESSION['titulo'],'R',1);//----AQUI SE IMPRIME LA DEPENDENCIA QUE DEBERIA VENIR SETEADA (SET) --- JUAN
	$this->SetFont('vera','B',12);
	$this->Cell(25,10,"",'L',0);
	$this->MultiCell(0,5,"RESUMEN DE LOS CRÉDITOS PRESUPUESTARIOS POR ACTIVIDADES",'R','C');
	$this->SetFont('vera','',7);
	$this->Cell(30,3,"",'L',0);
	$this->Cell(0,3,"(BOLIVAR FUERTE)",'R',1,'C');
	$this->SetFont('vera','',9);
	$this->Cell(28,5,"",'BL',0);
	$this->Cell(0,5,"PRESUPUESTO :".$_SESSION['ejercicio'],'RB',0);// <-- VARIABLE DE PRESUPUESTO AQUI
	$this->Ln(8);
  }//fin funtion
}//fin clases

function cerocero ($var) {
	$var= $var == "0,00" ? "" : $var;
	return $var;
}

$fpdf = new fpdfview('L','mm','Letter');
$fpdf->AliasNbPages();
$fpdf->SetTopMargin(30);
$fpdf->SetLeftMArgin(5);
$fpdf->SetRightMargin(5);
$fpdf->SetAutoPageBreak(true, 10);
$_SESSION['entidad_federal_aux'] = $entidad_federal;
$_SESSION['ejercicio'] = $ANO;
$_SESSION['titulo'] = $titulo_a;
$CONTADOR=0;

foreach($distintos_sectores as $vds){//Foreach #1
	//print_r($distintos_sectores);
	$s[1]=$vds[0]["cod_sector"];
	$s[2]=$vds[0]["cod_programa"];
	$s[3]=$vds[0]["cod_sub_prog"];
	$s[4]=$vds[0]["cod_proyecto"];
	$cod2_1=array(1=>$vds[0]["cod_sector"],2=>0,3=>0,4=>0);
	$cod2_2=array(1=>$vds[0]["cod_sector"],2=>$vds[0]["cod_programa"],3=>0,4=>0);
	$cod2_3=array(1=>$vds[0]["cod_sector"],2=>$vds[0]["cod_programa"],3=>$vds[0]["cod_sub_prog"],4=>0);
	$cod2_4=array(1=>$vds[0]["cod_sector"],2=>$vds[0]["cod_programa"],3=>$vds[0]["cod_sub_prog"],4=>$vds[0]["cod_proyecto"]);

	$aux=0;
	$contador=count($reporte_cfpd05_tmp_activ);
	foreach($reporte_cfpd05_tmp_activ as $rta){//Foreach #3
		$u[1]=$rta[0]['cod_partida'];
		$u[2]=$rta[0]['cod_generica'];
		$u[3]=$rta[0]['cod_especifica'];
		$u[4]=$rta[0]['cod_sub_espec'];
		$u[5]=$rta[0]['cod_auxiliar'];
		$var2[1]=$rta[0]['cod_sector'];
		$var2[2]=$rta[0]['cod_programa'];
		$var2[3]=$rta[0]['cod_sub_prog'];
		$var2[4]=$rta[0]['cod_proyecto'];

	     $cod1=$u[1];
	     $cod2=$u[2]>0 ? $sisap->AddCero3($u[2]) : "";
	     $cod3=$u[3]>0 ? $sisap->AddCero3($u[3]) : "";
	     if(($u[4]!=0 || $u[4]==0) && $u[5]!=0){
	     	$cod4= $sisap->AddCero3($u[4]);
	     	$cod5= $sisap->AddCero3($u[5]);
	     }else if($u[4]==0 && $u[5]==0){
	     	$cod4= "";
	     	$cod5= "";
	     }else if($u[4]!=0){
	     	$cod4= $sisap->AddCero3($u[4]);
	     }
	     //----Array auxiliar
	     $aux_vector [$aux][1]=$var2[1];//sector
	     $aux_vector [$aux][2]=$var2[2];//programa
	     $aux_vector [$aux][3]=$var2[3];//subprog
	     $aux_vector [$aux][4]=$var2[4];//proy
	     $aux_vector [$aux][6]=$u[1];//partida
	     $aux_vector [$aux][7]=$u[2];//generica
	     $aux_vector [$aux][8]=$u[3];//especifica
	     $aux_vector [$aux][9]=$u[4];//subespecifica
	     $aux_vector [$aux][10]=$u[5];//auxiliar
	     $ac=51;
	     $tfa=0;
	     for($jj=11;$jj<=31;$jj++){//for activ
	     	 if($jj==31){
	     	 	$aux_vector [$aux][$jj]=$tfa;//total monto actividades filas
	     	 }else{
	     	 	$aux_vector [$aux][$jj]=($rta[0]['activ'.$ac]=="" ? 0 : $rta[0]['activ'.$ac]);
	     	    $tfa=$tfa+$aux_vector [$aux][$jj];
	     	    $aux_vector [$aux][$jj]=($rta[0]['activ'.$ac]=="" ? "" : $rta[0]['activ'.$ac]);
	     	 }
	     	 $ac++;
	     }//fin for activ
	     $a = 0;
		$b = 0;
		$c = 0;
		$dd = 0;
		$e = 0;
		$j = 0;
		$codigos[1]=substr($u[1],-2);
        if($u[1]!=0 && $u[2]==0 && $u[3]==0 && $u[4]==0 && $u[5]==0){
			foreach($partida as $part){$a++;
						$part_aux[$a]['cod_grupo'] = $part['cfpd01_ano_partida']['cod_grupo'];
			  			$part_aux[$a]['cod_partida'] = $part['cfpd01_ano_partida']['cod_partida'];
			  			$part_aux[$a]['denominacion'] = $part['cfpd01_ano_partida']['denominacion'];
			  			if($part_aux[$a]['cod_grupo']==CE && $codigos[1]==$part_aux[$a]['cod_partida'] && $u[2]==0 && $u[3]==0 && $u[4]==0 && $u[5]==0){
			  				$deno=$part_aux[$a]['denominacion'];
			  				break;
			  			}
			}
	     }//partida
	     if($u[1]!=0 && $u[2]!=0 && $u[3]==0 && $u[4]==0 && $u[5]==0){
			foreach($generica as $gen){$b++;
						$gen_aux[$b]['cod_grupo'] = $gen['cfpd01_ano_generica']['cod_grupo'];
						$gen_aux[$b]['cod_partida'] = $gen['cfpd01_ano_generica']['cod_partida'];
						$gen_aux[$b]['cod_generica'] = $gen['cfpd01_ano_generica']['cod_generica'];
						$gen_aux[$b]['denominacion'] = $gen['cfpd01_ano_generica']['denominacion'];
						if($gen_aux[$b]['cod_grupo']==CE && $codigos[1]==$gen_aux[$b]['cod_partida'] && $u[2]==$gen_aux[$b]['cod_generica'] && $u[3]==0 && $u[4]==0 && $u[5]==0){
			  				$deno=$gen_aux[$b]['denominacion'];
			  				break;
			  			}
			     }
	        }//generica
	        if($u[1]!=0 && $u[2]!=0 && $u[3]!=0 && $u[4]==0 && $u[5]==0){
				foreach($especifica as $espec){$c++;
							$espec_aux[$c]['cod_grupo'] = $espec['cfpd01_ano_especifica']['cod_grupo'];
				         	$espec_aux[$c]['cod_partida'] = $espec['cfpd01_ano_especifica']['cod_partida'];
				         	$espec_aux[$c]['cod_generica'] = $espec['cfpd01_ano_especifica']['cod_generica'];
				         	$espec_aux[$c]['cod_especifica'] = $espec['cfpd01_ano_especifica']['cod_especifica'];
				         	$espec_aux[$c]['denominacion'] = $espec['cfpd01_ano_especifica']['denominacion'];
				         	if($espec_aux[$c]['cod_grupo']==CE && $codigos[1]==$espec_aux[$c]['cod_partida'] && $u[2]==$espec_aux[$c]['cod_generica'] && $u[3]==$espec_aux[$c]['cod_especifica'] && $u[4]==0 && $u[5]==0){
				  				$deno=$espec_aux[$c]['denominacion'];
				  				break;
				  			}
				}
	        }//especifica
	        if($u[1]!=0 && $u[2]!=0 && $u[3]!=0 && $u[4]!=0 && $u[5]==0){
				foreach($subespecifica as $subespec){$dd++;
					        $subespec_aux[$dd]['cod_grupo'] = $subespec['cfpd01_ano_sub_espec']['cod_grupo'];
				         	$subespec_aux[$dd]['cod_partida'] = $subespec['cfpd01_ano_sub_espec']['cod_partida'];
				         	$subespec_aux[$dd]['cod_generica'] = $subespec['cfpd01_ano_sub_espec']['cod_generica'];
				            $subespec_aux[$dd]['cod_especifica'] = $subespec['cfpd01_ano_sub_espec']['cod_especifica'];
				            $subespec_aux[$dd]['cod_sub_espec'] = $subespec['cfpd01_ano_sub_espec']['cod_sub_espec'];
				            $subespec_aux[$dd]['denominacion'] = $subespec['cfpd01_ano_sub_espec']['denominacion'];
				            if($subespec_aux[$dd]['cod_grupo']==CE && $codigos[1]==$subespec_aux[$dd]['cod_partida'] && $u[2]==$subespec_aux[$dd]['cod_generica'] && $u[3]==$subespec_aux[$dd]['cod_especifica'] && $u[4]==$subespec_aux[$dd]['cod_sub_espec'] && $u[5]==0){
				  				$deno=$subespec_aux[$dd]['denominacion'];
				  				break;
				  			}
				}
	        }//sub_espec
			if($u[1]!=0 && $u[2]!=0 && $u[3]!=0 && ($u[4]!=0 || $u[4]==0) && $u[5]!=0){
			     foreach($auxiliar as $vauxi){$j++;
						$vu[$j]['cod_sector'] = $vauxi['cfpd05_auxiliar']['cod_sector'];
			  			$vu[$j]['cod_programa'] = $vauxi['cfpd05_auxiliar']['cod_programa'];
			  			$vu[$j]['cod_sub_prog'] = $vauxi['cfpd05_auxiliar']['cod_sub_prog'];
			  			$vu[$j]['cod_proyecto'] = $vauxi['cfpd05_auxiliar']['cod_proyecto'];
			  			$vu[$j]['cod_activ_obra'] = $vauxi['cfpd05_auxiliar']['cod_activ_obra'];
			  			$vu[$j]['cod_partida'] = $vauxi['cfpd05_auxiliar']['cod_partida'];
			  			$vu[$j]['cod_generica'] = $vauxi['cfpd05_auxiliar']['cod_generica'];
			  			$vu[$j]['cod_especifica'] = $vauxi['cfpd05_auxiliar']['cod_especifica'];
			  			$vu[$j]['cod_sub_espec'] = $vauxi['cfpd05_auxiliar']['cod_sub_espec'];
			  			$vu[$j]['cod_auxiliar'] = $vauxi['cfpd05_auxiliar']['cod_auxiliar'];
			  			$vu[$j]['denominacion'] = $vauxi['cfpd05_auxiliar']['denominacion'];
			  			if($vu[$j]['cod_sector']==CE && $codigos[1]==$vu[$j]['cod_partida'] && $u[2]==$vu[$j]['cod_generica'] && $u[3]==$vu[$j]['cod_especifica'] && $u[4]==$vu[$j]['cod_sub_espec'] && $u[5]==$vu[$j]['cod_auxiliar']){
			  				    $deno="".$vu[$j]['denominacion'];
			  				    break;
			  			}
			      }
			}//auxiliar
	      $aux_vector [$aux][32]=$deno;

          $x = $fpdf->GetY();
	      if($s[1]==$var2[1] && $s[2]==$var2[2] && $s[3]==$var2[3] && $s[4]==$var2[4]){//Z
				if($x >= 190 || $aux==0){//X
				 $pasar=true;
				    $f=0;
					foreach($sector as $vsector){$f++;
								$va[$f]['cod_sector'] = $vsector['cfpd02_sector']['cod_sector'];
					  			$va[$f]['denominacion'] = $vsector['cfpd02_sector']['denominacion'];
					  			if($cod2_1[1]==$va[$f]['cod_sector'] && $cod2_1[2]==0 && $cod2_1[3]==0 && $cod2_1[4]==0){$denox=$va[$f]['denominacion'];break;}
					}
					$g=0;
					foreach($programa as $vprog){$g++;
								$ve[$g]['cod_sector'] = $vprog['cfpd02_programa']['cod_sector'];
					  			$ve[$g]['cod_programa'] = $vprog['cfpd02_programa']['cod_programa'];
					  			$ve[$g]['denominacion'] = $vprog['cfpd02_programa']['denominacion'];
					  			if($cod2_2[1]==$ve[$g]['cod_sector'] && $cod2_2[2]==$ve[$g]['cod_programa'] && $cod2_2[3]==0 && $cod2_2[4]==0){$denoy=$ve[$g]['denominacion'];break;}
				    }
					$h=0;
					foreach($subprograma as $vsupprog){$h++;
								$vi[$h]['cod_sector'] = $vsupprog['cfpd02_sub_prog']['cod_sector'];
					  			$vi[$h]['cod_programa'] = $vsupprog['cfpd02_sub_prog']['cod_programa'];
					  			$vi[$h]['cod_sub_prog'] = $vsupprog['cfpd02_sub_prog']['cod_sub_prog'];
					  			$vi[$h]['denominacion'] = $vsupprog['cfpd02_sub_prog']['denominacion'];
					  			if($cod2_3[1]==$vi[$h]['cod_sector'] && $cod2_3[2]==$vi[$h]['cod_programa'] && $cod2_3[3]==$vi[$h]['cod_sub_prog'] && $cod2_3[4]==0){$denoz=$vi[$h]['denominacion'];break;}
                    }
					$i=0;
					foreach($proyecto as $vproy){$i++;
								$vo[$i]['cod_sector'] = $vproy['cfpd02_proyecto']['cod_sector'];
					  			$vo[$i]['cod_programa'] = $vproy['cfpd02_proyecto']['cod_programa'];
					  			$vo[$i]['cod_sub_prog'] = $vproy['cfpd02_proyecto']['cod_sub_prog'];
					  			$vo[$i]['cod_proyecto'] = $vproy['cfpd02_proyecto']['cod_proyecto'];
					  			$vo[$i]['denominacion'] = $vproy['cfpd02_proyecto']['denominacion'];
					  			if($cod2_4[1]==$vo[$i]['cod_sector'] && $cod2_4[2]==$vo[$i]['cod_programa'] && $cod2_4[3]==$vo[$i]['cod_sub_prog'] && $cod2_4[4]==$vo[$i]['cod_proyecto']){$denos=$vo[$i]['denominacion'];break;}
					}
					$d[1]=$denox;
					$d[2]=$denoy;
					$d[3]=$denoz;
					$d[4]=$denos;
				        	$fpdf->AddPage();
						    $fpdf->SetFont('vera','B',8);//--
							$fpdf->Cell(40,5,"SECTOR: ",'TLRB',0,'L');
							$fpdf->Cell(25,5,$s[1],'TRB',0,'C');//----------------------[sector]
							$fpdf->SetFont('vera','',8);
							$fpdf->Cell(0,5,$d[1],'TRB',1,'L');

							$fpdf->SetFont('vera','B',8);//--
							$fpdf->Cell(40,5,"PROGRAMA: ",'LRB',0,'L');
							$fpdf->Cell(25,5,$s[2],'TRB',0,'C');//------------------------[programa]
							$fpdf->SetFont('vera','',8);
							$fpdf->Cell(0,5,$d[2],'TRB',1,'L');

							$fpdf->SetFont('vera','B',8);//--
							$fpdf->Cell(40,5,"SUB-PROGRAMA: ",'LRB',0,'L');
							$fpdf->Cell(25,5,$s[3],'TRB',0,'C');//-------------------[sub-programa]
							$fpdf->SetFont('vera','',8);
							$fpdf->Cell(0,5,$d[3],'TRB',1,'L');

							$fpdf->SetFont('vera','B',8);//--
							$fpdf->Cell(40,5,"PROYECTO: ",'LRB',0,'L');
							$fpdf->Cell(25,5,$s[4],'TRB',0,'C');//-----------------------[proyecto]
							$fpdf->SetFont('vera','',8);
							$fpdf->Cell(0,5,$d[4],'TRB',1,'L');
							$fpdf->Ln(2);

							$fpdf->SetFont('vera','B',7);//--
							$fpdf->Cell(41,4,"CÓDIGOS",'TRBL',0,'C');
							$fpdf->Cell(50,4,"",'TR',0,'C');
							//$fpdf->Cell(25,4,"",'TR',0,'C');
							$fpdf->Cell(0,4,"A C T I V I D A D E S",'TRB',1,'C');

							$fpdf->Cell(9,4,"",'RL',0,'C');
							$fpdf->Cell(32,4,"SUB - PARTIDAS",'TRBL',0,'C');
							$fpdf->Cell(50,4,"",'R',0,'C');
							$fpdf->Cell(16,4,"",'TR',0,'C');//
							$fpdf->Cell(16,4,"",'R',0,'C');
							$fpdf->Cell(16,4,"",'R',0,'C');
							$fpdf->Cell(16,4,"",'R',0,'C');
							$fpdf->Cell(16,4,"",'R',0,'C');
							$fpdf->Cell(16,4,"",'R',0,'C');
							$fpdf->Cell(16,4,"",'R',0,'C');
							$fpdf->Cell(16,4,"",'R',0,'C');
							$fpdf->Cell(16,4,"",'R',0,'C');
							$fpdf->Cell(16,4,"",'R',0,'C');
							$fpdf->Cell(0,4,"",'R',1,'C');

							$fpdf->Cell(9,5,"PART.",'RL',0,'C');
							$fpdf->Cell(8,5,"GEN.",'TR',0,'C');
							$fpdf->Cell(8,5,"ESP.",'TR',0,'C');
							$fpdf->Cell(8,5,"SUB",'TR',0,'C');
							$fpdf->Cell(8,5,"AUX.",'TR',0,'C');
							$fpdf->SetFont('vera','B',8);
							$fpdf->Cell(50,5,"D E N O M I N A C I Ó N",'R',0,'C');
							$fpdf->SetFont('vera','B',9);
							$fpdf->Cell(16,5,"51",'R',0,'C');///
							$fpdf->Cell(16,5,"52",'R',0,'C');
							$fpdf->Cell(16,5,"53",'R',0,'C');
							$fpdf->Cell(16,5,"54",'R',0,'C');
							$fpdf->Cell(16,5,"55",'R',0,'C');
							$fpdf->Cell(16,5,"56",'R',0,'C');
							$fpdf->Cell(16,5,"57",'R',0,'C');
							$fpdf->Cell(16,5,"58",'R',0,'C');
							$fpdf->Cell(16,5,"59",'R',0,'C');
							$fpdf->Cell(16,5,"60",'R',0,'C');
							$fpdf->Cell(0,5,"61",'R',1,'C');

							$fpdf->SetFont('vera','B',7);
							$fpdf->Cell(9,5,"",'RLB',0,'C');
							$fpdf->Cell(8,5,"",'RB',0,'C');
							$fpdf->Cell(8,5,"",'RB',0,'C');
							$fpdf->Cell(8,5,"ESP.",'RB',0,'C');
							$fpdf->Cell(8,5,"",'RB',0,'C');
							$fpdf->Cell(50,5,"",'RB',0,'C');
							$fpdf->Cell(16,5,"",'RB',0,'C');
							$fpdf->Cell(16,5,"",'RB',0,'C');
							$fpdf->Cell(16,5,"",'RB',0,'C');
							$fpdf->Cell(16,5,"",'RB',0,'C');
							$fpdf->Cell(16,5,"",'RB',0,'C');
							$fpdf->Cell(16,5,"",'RB',0,'C');
							$fpdf->Cell(16,5,"",'RB',0,'C');
							$fpdf->Cell(16,5,"",'RB',0,'C');
							$fpdf->Cell(16,5,"",'RB',0,'C');
							$fpdf->Cell(16,5,"",'RB',0,'C');
							$fpdf->Cell(0,5,"",'RB',1,'C');
							$fpdf->SetFont('vera','',7);
				        }//x
				        $letras = strlen($deno);//calculo el numero de letras del string que contiene $deno
					    if($letras <= 33){
							$heigth = "4";
					    }elseif(($letras >= 33) && ($letras < 67)){
					    	$heigth = "9";
					    }elseif(($letras >= 67) && ($letras < 101)){
					    	$heigth = "13";
					    }elseif(($letras >= 101) && ($letras < 135)){
					    	$heigth = "16";
					    }elseif($letras >= 135){
					    	$heigth = "20";
					    }
					    $aux_vector [$aux][33]=$heigth;//asigno altura de cada denominacion a la pos. 33 del vector aux.
						$aux++;
					    $CONTADOR++;
						$fpdf->Cell(9,$heigth,$cod1,'TRLB',0,'C');
						$fpdf->Cell(8,$heigth,$cod2,'TRB',0,'C');
						$fpdf->Cell(8,$heigth,$cod3,'TRB',0,'C');
						$fpdf->Cell(8,$heigth,$cod4,'TRB',0,'C');
						$fpdf->Cell(8,$heigth,"x",'TRB',0,'C');

						$varX = $fpdf->GetX();//asigno X
						$varY = $fpdf->GetY();//asigno Y
						$fpdf->Cell(50,1,"",'T',2,'C');
						$fpdf->MultiCell(50,3,$deno,'','J');//-------[denominacion]
						$varX = $varX+50;
						$fpdf->SetXY($varX,$varY);// cargo XY
						$act[1]=$rta[0]['activ51'];
						$act[2]=$rta[0]['activ52'];
						$act[3]=$rta[0]['activ53'];
						$act[4]=$rta[0]['activ54'];
						$act[5]=$rta[0]['activ55'];
						$act[6]=$rta[0]['activ56'];
						$act[7]=$rta[0]['activ57'];
						$act[8]=$rta[0]['activ58'];
						$act[9]=$rta[0]['activ59'];
						$act[10]=$rta[0]['activ60'];
						$act[11]=$rta[0]['activ61'];
						$fpdf->Cell(16,$heigth,$act[1],'TLRB',0,'R');//---------------------------[actividad_51]
						$fpdf->Cell(16,$heigth,$act[2],'TRB',0,'R');//----------------------------[actividad_52]
						$fpdf->Cell(16,$heigth,$act[3],'TRB',0,'R');//----------------------------[actividad_53]
						$fpdf->Cell(16,$heigth,$act[4],'TRB',0,'R');//----------------------------[actividad_54]
						$fpdf->Cell(16,$heigth,$act[5],'TRB',0,'R');//----------------------------[actividad_55]
						$fpdf->Cell(16,$heigth,$act[6],'TRB',0,'R');//----------------------------[actividad_56]
						$fpdf->Cell(16,$heigth,$act[7],'TRB',0,'R');//----------------------------[actividad_57]
						$fpdf->Cell(16,$heigth,$act[8],'TRB',0,'R');//----------------------------[actividad_58]
						$fpdf->Cell(16,$heigth,$act[9],'TRB',0,'R');//----------------------------[actividad_59]
						$fpdf->Cell(16,$heigth,$act[10],'TRB',0,'R');//---------------------------[actividad_60]
						$fpdf->Cell(0,$heigth,$act[11],'TRB',1,'R');//----------------------------[actividad_61]
						$fpdf->Cell(0,0,"",'T',1);

						 $x = $fpdf->GetY();
						 if($x>=190 && $pasar==true){//if J
							$fpdf->AddPage();
						    $fpdf->SetFont('vera','B',8);
							$fpdf->Cell(40,5,"SECTOR: ",'TLRB',0,'L');
							$fpdf->Cell(25,5,$s[1],'TRB',0,'C');//----------------------[sector]
							$fpdf->SetFont('vera','',8);
							$fpdf->Cell(0,5,$d[1],'TRB',1,'L');

							$fpdf->SetFont('vera','B',8);
							$fpdf->Cell(40,5,"PROGRAMA: ",'LRB',0,'L');
							$fpdf->Cell(25,5,$s[2],'TRB',0,'C');//------------------------[programa]
							$fpdf->SetFont('vera','',8);
							$fpdf->Cell(0,5,$d[2],'TRB',1,'L');

							$fpdf->SetFont('vera','B',8);
							$fpdf->Cell(40,5,"SUB-PROGRAMA: ",'LRB',0,'L');
							$fpdf->Cell(25,5,$s[3],'TRB',0,'C');//-------------------[sub-programa]
							$fpdf->SetFont('vera','',8);
							$fpdf->Cell(0,5,$d[3],'TRB',1,'L');

							$fpdf->SetFont('vera','B',8);
							$fpdf->Cell(40,5,"PROYECTO: ",'LRB',0,'L');
							$fpdf->Cell(25,5,$s[4],'TRB',0,'C');//-----------------------[proyecto]
							$fpdf->SetFont('vera','',8);
							$fpdf->Cell(0,5,$d[4],'TRB',1,'L');
							$fpdf->Ln(2);

							$fpdf->SetFont('vera','B',7);
							$fpdf->Cell(41,4,"CÓDIGOS",'TRBL',0,'C');
							$fpdf->Cell(50,4,"",'TR',0,'C');
							//$fpdf->Cell(25,4,"",'TR',0,'C');
							$fpdf->Cell(0,4,"A C T I V I D A D E S",'TRB',1,'C');

							$fpdf->Cell(9,4,"",'RL',0,'C');
							$fpdf->Cell(32,4,"SUB - PARTIDAS",'TRBL',0,'C');
							$fpdf->Cell(50,4,"",'R',0,'C');
							$fpdf->Cell(16,4,"",'TR',0,'C');
							$fpdf->Cell(16,4,"",'TR',0,'C');
							$fpdf->Cell(16,4,"",'TR',0,'C');
							$fpdf->Cell(16,4,"",'TR',0,'C');
							$fpdf->Cell(16,4,"",'R',0,'C');
							$fpdf->Cell(16,4,"",'R',0,'C');
							$fpdf->Cell(16,4,"",'R',0,'C');
							$fpdf->Cell(16,4,"",'R',0,'C');
							$fpdf->Cell(16,4,"",'R',0,'C');
							$fpdf->Cell(0,4,"",'R',1,'C');

							$fpdf->Cell(9,5,"PART.",'RL',0,'C');
							$fpdf->Cell(8,5,"GEN.",'TR',0,'C');
							$fpdf->Cell(8,5,"ESP.",'TR',0,'C');
							$fpdf->Cell(8,5,"SUB",'TR',0,'C');
							$fpdf->Cell(8,5,"AUX.",'TR',0,'C');
							$fpdf->SetFont('vera','B',8);
							$fpdf->Cell(50,5,"D E N O M I N A C I Ó N",'R',0,'C');
							$fpdf->SetFont('vera','B',9);
							$fpdf->Cell(16,5,"62",'R',0,'C');//1
							$fpdf->Cell(16,5,"63",'R',0,'C');//2
							$fpdf->Cell(16,5,"64",'R',0,'C');//3
							$fpdf->Cell(16,5,"65",'R',0,'C');//4
							$fpdf->Cell(16,5,"66",'R',0,'C');//5
							$fpdf->Cell(16,5,"67",'R',0,'C');//6
							$fpdf->Cell(16,5,"68",'R',0,'C');//7
							$fpdf->Cell(16,5,"69",'R',0,'C');//8
							$fpdf->Cell(16,5,"70",'R',0,'C');//
							$fpdf->Cell(0,5,"TOTAL",'R',1,'C');//

							$fpdf->SetFont('vera','B',7);
							$fpdf->Cell(9,5,"",'RLB',0,'C');
							$fpdf->Cell(8,5,"",'RB',0,'C');
							$fpdf->Cell(8,5,"",'RB',0,'C');
							$fpdf->Cell(8,5,"ESP.",'RB',0,'C');
							$fpdf->Cell(8,5,"",'RB',0,'C');
							$fpdf->Cell(50,5,"",'RB',0,'C');
							$fpdf->Cell(16,5,"",'RB',0,'C');//a62
							$fpdf->Cell(16,5,"",'RB',0,'C');//a63
							$fpdf->Cell(16,5,"",'RB',0,'C');//a64
							$fpdf->Cell(16,5,"",'RB',0,'C');//a65
							$fpdf->Cell(16,5,"",'RB',0,'C');//a66
							$fpdf->Cell(16,5,"",'RB',0,'C');//a67
							$fpdf->Cell(16,5,"",'RB',0,'C');//a68
							$fpdf->Cell(16,5,"",'RB',0,'C');//a69
							$fpdf->Cell(16,5,"",'RB',0,'C');//a70
							$fpdf->Cell(0,5,"",'RB',1,'C');
							$fpdf->SetFont('vera','',7);

							foreach($aux_vector as $aux_vector2){//for-x
							    $heigth2=$aux_vector2[33];
				                $fpdf->Cell(9,$heigth2,$aux_vector2[6],'TRLB',0,'C');
								$fpdf->Cell(8,$heigth2,$aux_vector2[7],'TRB',0,'C');
								$fpdf->Cell(8,$heigth2,$aux_vector2[8],'TRB',0,'C');
								$fpdf->Cell(8,$heigth2,$aux_vector2[9],'TRB',0,'C');
								$fpdf->Cell(8,$heigth2,"".count($aux_vector)."",'TRB',0,'C');

								$varX = $fpdf->GetX();//asigno X
								$varY = $fpdf->GetY();//asigno Y
								$fpdf->Cell(50,1,"",'T',2,'C');
								$fpdf->MultiCell(50,3,$aux_vector2[32],'','J');//-------[denominacion]
								$varX = $varX+50;//le sumo a X 50 del Cell debido a que lo capture antes.
								$fpdf->SetXY($varX,$varY);// cargo XY
								$act[1]=$aux_vector2[22];
								$act[2]=$aux_vector2[23];
								$act[3]=$aux_vector2[24];
								$act[4]=$aux_vector2[25];
								$act[5]=$aux_vector2[26];
								$act[6]=$aux_vector2[27];
								$act[7]=$aux_vector2[28];
								$act[8]=$aux_vector2[29];
								$act[9]=$aux_vector2[30];
								$act[10]=$aux_vector2[31];
								$fpdf->Cell(16,$heigth2,$act[1],'TLRB',0,'R');//------------------------------[actividad_62]
								$fpdf->Cell(16,$heigth2,$act[2],'TRB',0,'R');//-------------------------------[actividad_63]
								$fpdf->Cell(16,$heigth2,$act[3],'TRB',0,'R');//-------------------------------[actividad_64]
								$fpdf->Cell(16,$heigth2,$act[4],'TRB',0,'R');//-------------------------------[actividad_65]
								$fpdf->Cell(16,$heigth2,$act[5],'TRB',0,'R');//-------------------------------[actividad_66]
								$fpdf->Cell(16,$heigth2,$act[6],'TRB',0,'R');//-------------------------------[actividad_67]
								$fpdf->Cell(16,$heigth2,$act[7],'TRB',0,'R');//-------------------------------[actividad_68]
								$fpdf->Cell(16,$heigth2,$act[8],'TRB',0,'R');//-------------------------------[actividad_69]
								$fpdf->Cell(16,$heigth2,$act[9],'TRB',0,'R');//-------------------------------[actividad_70]
								$fpdf->Cell(0,$heigth2,$act[10],'TRB',1,'R');//-------------------------------[total]
								$fpdf->Cell(0,0,"",'T',1);
							}//fin for-x
							$aux=0;
							$aux_vector=null;
							$d=null;
						 }//fin if J
	      }//fin if Z
     }//fin Foreach #3
}//fin Foreach #1
$fpdf->OutPut('forma_2032_actividad'.date("d-m-Y"),'D');
?>