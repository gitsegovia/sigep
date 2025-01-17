<?php
extract($datos[0]['crcd01_actas_plantillas']);

//vendor('tcpdf/config/lang/eng.php');
vendor('tcpdf/tcpdf');
//App::import('Vendor', 'tcpdf/config/lang/eng.php');
//App::import('Vendor', 'tcpdf/tcpdf');

class TESIS_PDF extends TCPDF {
	public function Header() {
		/*$this->Image(K_PATH_IMAGES.'unerg.png', 25, 10,38);
		$this->Image(K_PATH_IMAGES.'ais.png', 165, 10,20);
		$html_top = '<br/><br/><span style="text-align:center;font-size:9pt;">REPÚBLICA BOLIVARIANA DE VENEZUELA<br/>UNIVERSIDAD RÓMULO GALLEGOS<br />' .
				    'ÁREA DE INGENIERÍA DE SISTEMAS<br /></span>';

        $html_top .='<span style="text-align:center;"><h3>ACTA DEL CONSEJO DE ÁREA</h3><h4>SESIÓN EXTRAORDINARIA<br />Nro. '.$_SESSION['codigo_acta'].'<br />HORA '.$_SESSION['hora'].'</h4></span>';
		$this->SetFont('helvetica', 'B', 10);
        $this->writeHTML($html_top, true, 0, true, true);
		// Set font
		$this->SetFont('helvetica', 'B', 20);
		// Move to the right
		$this->Cell(80);
		// Title
		$this->Cell(30, 10, '', 0, 0, 'C');*/
		// Line break
		$this->Ln(20);
	}

	// Page footer
	public function Footer() {
		// Position at 1.5 cm from bottom
		$this->SetY(-10);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 0, 'C');
	}
}


// create new PDF document
$pdf = new TESIS_PDF('P', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('ACTA');
$pdf->SetTitle('ACTA');
$pdf->SetSubject('Tesis');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins('20', '10', '10');//PDF_MARGIN_RIGHT
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, '10');
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// create some HTML content

$pdf->AddPage();
$pdf->Ln(10);
$pdf->writeHTML($contenido_acta, true, 0, true, true);
$pdf->lastPage();

//Close and output PDF document
$pdf->Output('Acta.pdf', 'D');
?>
