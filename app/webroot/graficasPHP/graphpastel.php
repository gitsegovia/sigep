<?
Header( "Content-type: image/png");
//Header( "Content-type: image/jpeg");
require("grab_globals.lib.php");


function Formato2($monto){
       $aux = $monto.'';
       $monto =  sprintf("%01.3f",$monto);
        for($i=0; $i<strlen($aux); $i++){
        	  if($aux[$i]=='.'){
        	  	 if(isset($aux[$i+3])){
        	  	 	if($aux[$i+3]=='5'){$monto += 0.001; break;}
        	  	 	}
        	  	 }
        	  }//fin for
    	$var = number_format($monto,2,",",".");
    	return $var;
}//fin function


if($bkg == "") $bkg="FFFFFF";
if($wdt == "") $wdt=200;
if($hgt == "") $hgt=100;

	$por = array();
	$dato = array();
	$dato = split(",", str_replace(" ","",$dat));


	/* crea imagen */
	$image = imagecreate($wdt +1,$hgt+21);

	// librerias de Colores y Funciones
	include('libcolores.php');
	include('libfunciones.php');

	sscanf($bkg, "%2x%2x%2x", $rr, $gg, $bb);
	$colorbkg = ImageColorAllocate($image,$rr,$gg,$bb);

	// crea bkg blanco
	ImageFilledRectangle($image,0,0,$wdt +1,$hgt+21,$colorbkg);



	$nvars = 0;
	foreach ($dato as $valor) {
		$nvars++;
	}
	for ($i = 0;$i < $nvars;$i++){
		$dato_aux[$i] = $dato[$i];
	}
	for ($i = 0;$i < $nvars;$i++){
		$total += $dato_aux[$i];
	}
    for ($i = 0;$i < $nvars;$i++){
		$por_aux[$i] = ($dato_aux[$i] * 360) / $total;
	}


	$total     = 0;
	$nvars_aux = 0;
	for ($i = 0;$i < $nvars;$i++){
		if($por_aux[$i]>=1){
           $dato_aux2[$nvars_aux] = $dato_aux[$i];
           $total                += $dato_aux[$i];
           $nvars_aux++;
           $activa[$i]     = 1;
		}else{
           $activa[$i]     = 0;
		}
	}
    for ($i = 0;$i < $nvars_aux; $i++){
		$por[$i] = ($dato_aux2[$i] * 360) / $total;
	}


	$inicio = 0;
	$final  = 0;
	$contar = 0;

	for ($j = ($hgt/2)+15;$j > $hgt/2;$j--) { $contar = 0;
		for ($i = 0, $c = 6;$i < $nvars;$i++,$c+=3) {
			if($activa[$i]!=0){
				$final += $por[$contar];
				imagefilledarc ($image, $wdt/2, $j, $wdt, $hgt, $inicio, $final, $colores[$c], IMG_ARC_PIE);
				$inicio = $final;
				$contar++;
			}
		}
	}

	$inicio = 0;
	$final  = 0;
	$contar = 0;

	for ($i = 0, $c = 5;$i < $nvars;$i++, $c+=3) {
		 if($activa[$i]!=0){
			$final += $por[$contar];
			imagefilledarc ($image, $wdt/2, $hgt/2, $wdt, $hgt, $inicio, $final, $colores[$c], IMG_ARC_PIE);
			$inicio = $final;
			$contar++;
		 }
	}

/* Realiza la generacion del grafico */
ImagePNG($image);
$username = $usr;
ImagePNG($image, '/var/www/sisap/app/tmp/pastel_tipo_gasto_'.$username.'_'.$rdm.'.png');
//ImageJPEG($image,'',100);

/* Vacia la memoria */
ImageDestroy($image);
?>