<?php
/*
 * Creado el  05/11/2007 a las 11:55:36 AM
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */

 //So, now we ready to build the controller and to implement the add()
 // app/controllers/files_controller.php

class FilesController extends AppController
 {
 	//var $helpers = array('Sisap');
 	var $uses = array('File','cnmd06_imagenes');
 function index () {

}
 function add()
 {
 	if (!empty($this->params['form']) && is_uploaded_file($this->params['form']['File']['tmp_name']))
 	{
 		if($this->params['form']['File']['type']=="image/jpeg"){


	    $fileDataOriginal = fread(fopen($this->params['form']['File']['tmp_name'],"r"), $this->params['form']['File']['size']);
	    $fileDataMini = fread(fopen($this->redimensionar($this->params['form']['File']['tmp_name'],160,0),"r"), $this->params['form']['File']['size']);

	    $this->params['form']['File']['data'] = base64_encode($fileDataMini);
	    $this->params['form']['File']['original'] = base64_encode($fileDataOriginal);

	    $this->File->save($this->params['form']['File']);
        //echo "La imagen se a cargado exitosamente...";
        //echo $this->params['form']['File']['tmp_name'];
        $x=$this->redimensionar($this->redimensionar($this->params['form']['File']['tmp_name'],160,0),160,1);
	    $this->ver($x);
	    $this->render("ver","img");


	    //$this->set('Dataimg',$this->File->findAll());

	    /*echo "".$this->params['form']['File']['tmp_name'];

	    echo "<br> ".$this->params['form']['File']['type'];

	    echo "<br> ".$this->params['form']['File']['size'];*/

	    //$this->redirect('somecontroller/someaction');
 		}else{
 			echo "Por Favor Cargue una imagen que sea de tipo JPG";
 			echo "<br>Tipo Imagen:".$this->params['form']['File']['type'];
 			echo "<br>Tamaño: ".$this->params['form']['File']['size'];
 		}
    }
}




 function ver($dataimg) {
 	$this->layout="img";
 	$this->set('img',$dataimg);
 }

 function viewid ($id) {
    $this->layout="img";
    $vec=$this->cnmd06_imagenes->findByCedula($id);
    $dataimg=$vec["cnmd06_imagenes"]["foto"];
    $this->set('img',$dataimg);
}
function grande ($id) {
    $this->layout="img";
    $vec=$this->cnmd06_imagenes->findByCedula($id);
    $dataimg=$vec["cnmd06_imagenes"]["foto"];
    $this->set('img',$dataimg);
}
function galeria () {
	$data=$this->cnmd06_imagenes->findAll(null,array('cedula'));
	$this->set("dataimg",$data);
}


/**
 * Funcion para redimensionar las imagenes.
 *
 */
function redimensionar($imagen,$largo,$mostrar = 1)
{
 	// $imagen	Ruta de la Imagen a Redimensioanr
 	// $largo	Largo de la Redimension
	// $mostrar	1 Muestra la Imagen en el Nevegador
	// $mostrar	0 Guarda la Imagen

	// Si $mostrar es 0
	// Funcion devuelve ruta de la Imagen


	$anchura=$largo;
	// Altura Maxima de la Imagen
	$hmax=400;
	$nombre=$imagen;
	$datos = getimagesize($nombre);



	if($datos[2]==1){
	 	$img = @imagecreatefromgif($nombre);
	}

	if($datos[2]==2){
	 	$img = @imagecreatefromjpeg($nombre);
	}

	if($datos[2]==3){
	 	$img = @imagecreatefrompng($nombre);
	}

	$ratio = ($datos[0] / $anchura);

	$altura = ($datos[1] / $ratio);

	if($altura>$hmax){
	 	$anchura2=$hmax*$anchura/$altura;
		 $altura=$hmax;
		 $anchura=$anchura2;
	}

	$thumb = imagecreatetruecolor($anchura,$altura);

	imagecopyresampled($thumb, $img, 0, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1]);


	// Creamos la Imagen (un JPG)
	if ( $mostrar == 0 ){
	 	// Guardamos Imagen en Directorio
	  	imagejpeg($thumb,$imagen.'_copy',100);
	  	imagedestroy($thumb);
	  	return ($imagen.'_copy');//devuelve ruta de la imagen creada

	}else{
	 	// Mostramos Imagen en el Navegador
		//header("Content-type: image/jpeg");
		return imagejpeg($thumb,'',100);

	}
	imagedestroy($thumb);
}//fin funcion redimensionar
}// fin del controlador FilesController
?>
