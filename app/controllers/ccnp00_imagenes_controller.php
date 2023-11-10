<?php

 class Ccnp00ImagenesController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','ccnd00_imagenes');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


	function checkSession(){

				if (!$this->Session->check('concejo_comunal')){
							$this->redirect('/salir');
							exit();
		}else{
			$this->requestAction('/usuarios/actualizar_user');
		}
	}//fin checksession


	function beforeFilter(){
		$this->checkSession();
		if($this->ano_ejecucion()!=""){
			return;
		}else{
			//echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
			//exit();
		}
	}

	 function filtro(){
		$cod_republica     = $this->Session->read('CC_republica');
		$cod_estado        = $this->Session->read('CC_estado');
		$cod_municipio     = $this->Session->read('CC_municipio');
		$cod_parroquia     = $this->Session->read('CC_parroquia');
		$cod_centro        = $this->Session->read('CC_centro');
		$cod_concejo       = $this->Session->read('CC_concejo');
		$ano               = $this->Session->read('concejos_comunal_id1');
	    $cod_proyecto      = $this->Session->read('concejos_comunal_id2');

		return $conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and identificacion='".$cod_proyecto."' ";

	 }

	  function dfiltro($v){

		if($v==1) return $this->Session->read('CC_republica');
		if($v==2) return $this->Session->read('CC_estado');
		if($v==3) return $this->Session->read('CC_municipio');
		if($v==4) return $this->Session->read('CC_parroquia');
		if($v==5) return $this->Session->read('CC_centro');
		if($v==6) return $this->Session->read('CC_concejo');
		if($v==7) return $this->Session->read('concejos_comunal_id1');
	    if($v==8) return $this->Session->read('concejos_comunal_id2');

	 }

	function index ($mostrar=null,$opcion=null,$id=null,$id_mostrar_capa=null,$fun_accion=null) {
	    $this->layout="ajax";
	    //echo $id;
	    $this->set('mostrar',$mostrar);
	    if(isset($id) && isset($opcion)){
	       $this->Session->write("identificacion",$id);
	       $this->set("identificacion",$id);
	       $this->set("opcion",$opcion);
	       $this->set("ID_CAPAMOSTRAR",$id_mostrar_capa);
	       $this->set("funcion_pag",$fun_accion);
	    }


	    //$this->set("i",$i);
	}



 function add($id_capa=null,$opcion=null,$aleatorio=null){
 	if (!empty($this->params['form']) && is_uploaded_file($this->params['form']['File_'.$aleatorio]['tmp_name']) && isset($_SESSION['identificacion']) && isset($opcion)){
 		if($this->params['form']['File_'.$aleatorio]['type']=="image/jpeg"){
               $var_alert=$opcion;
               $archivo_nuevo=$this->redimensionar($this->params['form']['File_'.$aleatorio]['tmp_name'],500,0);
               $fp=fopen($archivo_nuevo,"rb");
               $archivo_miniatura=$this->redimensionar($this->params['form']['File_'.$aleatorio]['tmp_name'],100,0);
               $fp_miniatura=fopen($archivo_miniatura,"rb");
			    $fileDataOriginal = fread($fp, filesize($archivo_nuevo));
                fclose($fp);
                $fileDataOriginalMiniatura = fread($fp_miniatura, filesize($archivo_miniatura));
                fclose($fp_miniatura);
			    $tipo=$this->params['form']['File_'.$aleatorio]['type'];
			    $size=filesize($archivo_nuevo);
			    $fecha=date("Y-m-d");

			    $fileDataGuardar=pg_escape_bytea($fileDataOriginal);
			    $fileDataGuardarMiniatura=pg_escape_bytea($fileDataOriginalMiniatura);

			    $SQL_condicion=$this->filtro()." and cod_campo=".$opcion;

			    $c=$this->ccnd00_imagenes->findCount($SQL_condicion);
			    if($c==0){
			        $campos[]=$this->dfiltro(1);
			        $campos[]=$this->dfiltro(2);
			        $campos[]=$this->dfiltro(3);
			        $campos[]=$this->dfiltro(4);
			        $campos[]=$this->dfiltro(5);
			        $campos[]=$this->dfiltro(6);
			        $campos[]=$opcion;
			        $campos[]="'".$this->dfiltro(8)."'";
			        $campos[]="'".$fileDataGuardar."'";
			        $campos[]="'".$tipo."'";
			        $campos[]=$size;
			        $campos[]="'".$fecha."'";
			        $campos[]="'".$fileDataGuardarMiniatura."'";
			        $campos[]=$this->dfiltro(7);
                    $SQL_INSERT="INSERT INTO ccnd00_imagenes(cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, cod_campo, identificacion, imagen, tipo, size, fecha, imagen_miniatura, ano) VALUES (".implode(',',$campos).");";
			        $this->ccnd00_imagenes->execute($SQL_INSERT);
			    }else{
                    $this->set('errorMessage', 'Ya existe una imagen para este registro');
			    }

                $this->set("identificacion",$this->dfiltro(8));
                $this->set('opcion',$opcion);
                //echo "asdasd";
          unset($fp);
          unset($fp_miniatura);
          unset($fileDataOriginal);
          unset($fileDataOriginalMiniatura);
          unset($fileDataGuardar);
          unset($fileDataGuardarMiniatura);
          unset($SQL_INSERT);
          unset($campos);
 		}else{
 			echo "Por Favor Cargue una imagen que sea de formato tipo JPG";
 			echo "<br>Tipo Imagen:".$this->params['form']['File_'.$aleatorio]['type'];
 			echo "<br>Tamaño: ".$this->params['form']['File_'.$aleatorio]['size'];
 		}
    }
    $this->set("identificacion",$this->dfiltro(8));
    $this->set('opcion',$opcion);
    $this->set('IDCAPA',$id_capa);
    //echo "<script>alert('".$this->Session->read('identificacion')."');</script>";

}///fin add

function modificar($id_capa=null,$opcion=null,$aleatorio=null){
 	if (!empty($this->params['form']) && is_uploaded_file($this->params['form']['File_'.$aleatorio]['tmp_name']) && isset($_SESSION['identificacion']) && isset($opcion)){
 		if($this->params['form']['File_'.$aleatorio]['type']=="image/jpeg"){
                 $var_alert=$opcion;
               	 $archivo_nuevo=$this->redimensionar($this->params['form']['File_'.$aleatorio]['tmp_name'],500,0);
               	 $fp=fopen($archivo_nuevo,"rb");
               	 $archivo_miniatura=$this->redimensionar($this->params['form']['File_'.$aleatorio]['tmp_name'],120,0);
               	 $fp_miniatura=fopen($archivo_miniatura,"rb");
			    $fileDataOriginal = fread($fp, filesize($archivo_nuevo));
                fclose($fp);
                $fileDataOriginalMiniatura = fread($fp_miniatura, filesize($archivo_miniatura));
                fclose($fp_miniatura);
			    $tipo=$this->params['form']['File_'.$aleatorio]['type'];
			    $size=filesize($archivo_nuevo);
			    $fecha=date("Y-m-d");

			    $fileDataGuardar=pg_escape_bytea($fileDataOriginal);
			    $fileDataGuardarMiniatura=pg_escape_bytea($fileDataOriginalMiniatura);
			    $SQL_UPDATE="UPDATE ccnd00_imagenes SET imagen='".$fileDataGuardar."', tipo='".$tipo."',size=".$size.",fecha='".$fecha."',imagen_miniatura='".$fileDataGuardarMiniatura."' ";
			    $SQL_UPDATE.="WHERE ".$this->filtro()."  and cod_campo=".$opcion.";";
			    $SQL_condicion=$this->filtro()."  and cod_campo=".$opcion."";

			    $c=$this->ccnd00_imagenes->findCount($SQL_condicion);
			    if($c==0){
			        $campos[]=$this->dfiltro(1);
			        $campos[]=$this->dfiltro(2);
			        $campos[]=$this->dfiltro(3);
			        $campos[]=$this->dfiltro(4);
			        $campos[]=$this->dfiltro(5);
			        $campos[]=$this->dfiltro(6);
			        $campos[]=$opcion;
			        $campos[]="'".$this->dfiltro(8)."'";
			        $campos[]="'".$fileDataGuardar."'";
			        $campos[]="'".$tipo."'";
			        $campos[]=$size;
			        $campos[]="'".$fecha."'";
			        $campos[]="'".$fileDataGuardarMiniatura."'";
			        $campos[]=$this->dfiltro(7);
                    $SQL_INSERT="INSERT INTO ccnd00_imagenes(cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, cod_campo, identificacion, imagen, tipo, size, fecha, imagen_miniatura, ano) VALUES (".implode(',',$campos).");";
			        $this->ccnd00_imagenes->execute($SQL_INSERT);
			    }
			    $this->ccnd00_imagenes->execute($SQL_UPDATE);
                $this->set("identificacion",$this->dfiltro(8));
                $this->set('opcion',$opcion);
	              unset($fp);
		          unset($fp_miniatura);
		          unset($fileDataOriginal);
		          unset($fileDataOriginalMiniatura);
		          unset($fileDataGuardar);
		          unset($fileDataGuardarMiniatura);
		          unset($SQL_INSERT);
		          unset($campos);
		          unset($SQL_UPDATE);
 		}else{
 			echo "Por Favor Cargue una imagen que sea de formato tipo JPG";
 			echo "<br>Tipo Imagen:".$this->params['form']['File_'.$aleatorio]['type'];
 			echo "<br>Tamaño: ".$this->params['form']['File_'.$aleatorio]['size'];
 		}
    }else{
    	//echo "";
    }
    //echo "";
    $this->set("identificacion",$this->dfiltro(8));
    $this->set('opcion',$opcion);
    $this->set('IDCAPA',$id_capa);
    //echo "<script>alert('".$var_alert."');</script>";

}///fin modificar




 function ver($id,$opcion=null,$var=null) {
 	$this->layout="images";
 	$aleatorio=intval(rand());
    if(isset($id) && isset($opcion)){
	  	$cantidad=$this->ccnd00_imagenes->findCount($this->filtro()." and cod_campo=".$opcion."");
	  	$rs_img=$this->ccnd00_imagenes->execute("SELECT coalesce(imagen,'-1') as imagen,tipo FROM ccnd00_imagenes WHERE  ".$this->filtro()." and cod_campo=".$opcion." and ".$aleatorio."=".$aleatorio);
	 	if($cantidad==0){
          $rs_img=$this->ccnd00_imagenes->execute("SELECT coalesce(imagen,'-1') as imagen,tipo FROM ccnd00_imagenes WHERE  cod_campo=0 and identificacion='0'");
 	    }
	 	$_SESSION["MIME"]=$rs_img[0][0]["tipo"];
	  	$this->set("data_img",pg_unescape_bytea($rs_img[0][0]["imagen"]));
 	}
 	unset($rs_img);
 }//fin ver

  function ver_miniatura($id,$opcion=null,$var=null) {
 	$this->layout="images";
 	$aleatorio=intval(rand());
 	if(isset($id) && isset($opcion)){
	  	$cantidad=$this->ccnd00_imagenes->findCount($this->filtro()." and cod_campo=".$opcion."");
	  	$rs_img=$this->ccnd00_imagenes->execute("SELECT coalesce(imagen_miniatura,'-1') as imagen,tipo FROM ccnd00_imagenes WHERE  ".$this->filtro()." and cod_campo=".$opcion." and ".$aleatorio."=".$aleatorio);
	 	if($rs_img[0][0]["imagen"]==''){
	 		$rs_img=$this->ccnd00_imagenes->execute("SELECT coalesce(imagen,'-1') as imagen,tipo FROM ccnd00_imagenes WHERE  ".$this->filtro()." and cod_campo=".$opcion." and ".$aleatorio."=".$aleatorio);
	 	}
	 	if($cantidad==0){
          $rs_img=$this->ccnd00_imagenes->execute("SELECT coalesce(imagen,'-1') as imagen,tipo FROM ccnd00_imagenes WHERE  cod_campo=0 and identificacion='0'");
 	    }
	 	$_SESSION["MIME"]=$rs_img[0][0]["tipo"];
	  	$this->set("data_img",pg_unescape_bytea($rs_img[0][0]["imagen"]));
 	}

 	unset($rs_img);


 }//fin ver_miniatura

  function eliminar($id,$opcion) {
  		$cantidad=$this->ccnd00_imagenes->findCount($this->filtro()." and cod_campo=".$opcion."");
	 	if($cantidad!=0){
	         $rs_img=$this->ccnd00_imagenes->execute("DELETE FROM ccnd00_imagenes WHERE  ".$this->filtro()." and cod_campo=".$opcion."");
	 	}else {
	 		$rs_img =0;
	 	}
        return $rs_img;
 }//fin eliminar

   function eliminar_imagen($id,$opcion) {
   	$this->layout="ajax";

  		$cantidad=$this->ccnd00_imagenes->findCount($this->filtro()." and cod_campo=".$opcion."");
	 	if($cantidad!=0){
	         $rs_img=$this->ccnd00_imagenes->execute("DELETE FROM ccnd00_imagenes WHERE  ".$this->filtro()." and cod_campo=".$opcion."");
	 	}else {
	 		$rs_img =0;
	 	}
  	  if($rs_img>0){
        echo "Imagen eliminada exitosamente [$rs_img]";
  	  }else{
  	  	echo "Imagen no eliminada [$rs_img]";
  	  }

 }//fin eliminar

 function ver_imagen_id ($id=null,$opcion=null) {
	$this->layout="ajax";
	if(isset($id) && $id!=null && isset($opcion) && $opcion!=null){
	$this->set("id",$id);
	$this->set('opcion',$opcion);
	}
}

 function ver_imagen_grande ($id=null,$opcion=null) {

	$this->layout="ajax";
	if(isset($id) && $id!=null && isset($opcion) && $opcion!=null){
	$this->set("id",$id);
	$this->set('opcion',$opcion);
	}
}

function galeria () {
	$data=$this->ccnd00_imagenes->findAll();
	$this->set("dataimg",$data);
}

  function ver_miniatura_galeria($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_campo,$identificacion) {
 	$this->layout="images";
 	$aleatorio=intval(rand());
	  	$rs_img=$this->ccnd00_imagenes->execute("SELECT coalesce(imagen_miniatura,'-1') as imagen,tipo FROM ccnd00_imagenes WHERE  ".$this->filtro()." and cod_campo=".$cod_campo."");
	 	$_SESSION["MIME"]=$rs_img[0][0]["tipo"];
	  	$this->set("data_img",pg_unescape_bytea($rs_img[0][0]["imagen"]));

 	unset($rs_img);


 }//fin ver_miniatura

function redimensionar($imagen,$largo,$mostrar = 1){
 	// $imagen	Ruta de la Imagen a Redimensioanr
 	// $largo	Largo de la Redimension
	// $mostrar	1 Muestra la Imagen en el Nevegador
	// $mostrar	0 Guarda la Imagen

	// Si $mostrar es 0
	// Funcion devuelve ruta de la Imagen
    $aleatorio=intval(rand());

	$anchura=$largo;
	// Altura Maxima de la Imagen
	$hmax=768;
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
	  	imagejpeg($thumb,$imagen.'_'.$aleatorio.'_copy',100);
	  	imagedestroy($thumb);
	  	return ($imagen.'_'.$aleatorio.'_copy');//devuelve ruta de la imagen creada
	}else{
	 	// Mostramos Imagen en el Navegador
		//header("Content-type: image/jpeg");
		return imagejpeg($thumb,'',100);
	}
	imagedestroy($thumb);
}//fin funcion redimensionar




function prueba () {
      $this->layout="ajax";

}




}//fin class
?>
