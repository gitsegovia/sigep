<?php

 class InfoImagenesController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','cugd10_imagenes');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');

function checkSession(){
				if (!$this->Session->check('infogobierno')){
						$this->redirect('/infogobierno/salir_todo');
						exit();
				}
}//fin checksession

 function beforeFilter(){
 	$this->checkSession();
 }

function verifica_SS($i){
			/**
			 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
			 * para ser insertados en todas las tablas.
			 * */
			switch ($i){
				case 1:return 1;break;
				case 2:return 1;break;
				case 3:return 1;break;
				case 4:return 1;break;
				case 5:return 1;break;
				case 6:return '';break;
				default:
					 return "NULO";


			}//fin switch
		}//fin verifica_SS
	function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
	         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
	         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
	         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
	         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
	         if($ano!=null){
	         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
	            $sql_re .= "ano=".$ano."  ";
	         }else{
	         	$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
	         }
	         return $sql_re;
	}//fin funcion SQLCA

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

			    if($opcion==11 || $opcion==20){
                    $SQL_condicion="cod_campo=".$opcion." and identificacion='".$this->Session->read('identificacion')."'";
			    }else{
			    	$SQL_condicion="cod_presi=".$this->verifica_SS(1)." and cod_entidad=".$this->verifica_SS(2)." and cod_tipo_inst=".$this->verifica_SS(3)." and cod_inst=".$this->verifica_SS(4)." and cod_dep=".$this->verifica_SS(5)." and cod_campo=".$opcion." and identificacion='".$this->Session->read('identificacion')."'";
			    }
			    $c=$this->cugd10_imagenes->findCount($SQL_condicion);
			    if($c==0){
			        $campos[]=1;
			        $campos[]=1;
			        $campos[]=1;
			        $campos[]=1;
			        $campos[]=1;
			        $campos[]=$opcion;
			        $campos[]="'".$this->Session->read('identificacion')."'";
			        $campos[]="'".$fileDataGuardar."'";
			        $campos[]="'".$tipo."'";
			        $campos[]=$size;
			        $campos[]="'".$fecha."'";
			        $campos[]="'".$fileDataGuardarMiniatura."'";
                    $SQL_INSERT="INSERT INTO cugd10_imagenes(cod_presi, cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_campo,identificacion,imagen,tipo,size,fecha,imagen_miniatura) VALUES (".implode(',',$campos).");";
			        $this->cugd10_imagenes->execute($SQL_INSERT);
			    }else{
                    $this->set('errorMessage', 'Ya existe una imagen para este registro');
			    }

                $this->set("identificacion",$this->Session->read('identificacion'));
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
    $this->set("identificacion",$this->Session->read('identificacion'));
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
			    $SQL_UPDATE="UPDATE cugd10_imagenes SET imagen='".$fileDataGuardar."', tipo='".$tipo."',size=".$size.",fecha='".$fecha."',imagen_miniatura='".$fileDataGuardarMiniatura."' ";
			    if($opcion==11 || $opcion==20){
                    $SQL_UPDATE.="WHERE  cod_campo=".$opcion." and identificacion='".$this->Session->read('identificacion')."';";
			        $SQL_condicion="cod_campo=".$opcion." and identificacion='".$this->Session->read('identificacion')."'";
			    }
			    $c=$this->cugd10_imagenes->findCount($SQL_condicion);
			    if($c==0){
			        $campos[]=1;
			        $campos[]=1;
			        $campos[]=1;
			        $campos[]=1;
			        $campos[]=1;
			        $campos[]=$opcion;
			        $campos[]="'".$this->Session->read('identificacion')."'";
			        $campos[]="'".$fileDataGuardar."'";
			        $campos[]="'".$tipo."'";
			        $campos[]=$size;
			        $campos[]="'".$fecha."'";
			        $campos[]="'".$fileDataGuardarMiniatura."'";
                    $SQL_INSERT="INSERT INTO cugd10_imagenes(cod_presi, cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_campo,identificacion,imagen,tipo,size,fecha,imagen_miniatura) VALUES (".implode(',',$campos).");";
			        $this->cugd10_imagenes->execute($SQL_INSERT);
			    }else{
                    //$this->set('errorMessage', 'Ya existe una imagen para este registro');
			    }
                //echo "<textarea>".$SQL_UPDATE."</textarea>";
			    $this->cugd10_imagenes->execute($SQL_UPDATE);
                $this->set("identificacion",$this->Session->read('identificacion'));
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
    $this->set("identificacion",$this->Session->read('identificacion'));
    $this->set('opcion',$opcion);
    $this->set('IDCAPA',$id_capa);
    //echo "<script>alert('".$var_alert."');</script>";

}///fin modificar




 function ver($id,$opcion=null,$var=null) {
 	$this->layout="images";
 	$aleatorio=intval(rand());
 	if(isset($id) && isset($opcion) && ($opcion==11 || $opcion==20)){
 		$cantidad=$this->cugd10_imagenes->findCount("cod_campo=".$opcion."  and identificacion='".$id."'");
        $rs_img=$this->cugd10_imagenes->execute("SELECT coalesce(imagen,'-1') as imagen,tipo FROM cugd10_imagenes WHERE  cod_campo=".$opcion." and identificacion='".$id."' and ".$aleatorio."=".$aleatorio);
 	    if($cantidad==0){
          $rs_img=$this->cugd10_imagenes->execute("SELECT coalesce(imagen,'-1') as imagen,tipo FROM cugd10_imagenes WHERE  cod_campo=0 and identificacion='0'");
 	    }
 	    $_SESSION["MIME"]=$rs_img[0][0]["tipo"];
  	    $this->set("data_img",pg_unescape_bytea($rs_img[0][0]["imagen"]));
 	}
 	unset($rs_img);
 }//fin ver

  function ver_miniatura($id,$opcion=null,$var=null) {
 	$this->layout="images";
 	$aleatorio=intval(rand());
 	if(isset($id) && isset($opcion) && ($opcion==11 || $opcion==20)){
 		$cantidad=$this->cugd10_imagenes->findCount("cod_campo=".$opcion."  and identificacion='".$id."'");
        $rs_img=$this->cugd10_imagenes->execute("SELECT coalesce(imagen_miniatura,'-1') as imagen,tipo FROM cugd10_imagenes WHERE  cod_campo=".$opcion." and identificacion='".$id."' and ".$aleatorio."=".$aleatorio);
 	    if($rs_img[0][0]["imagen"]==''){
 	    	$rs_img=$this->cugd10_imagenes->execute("SELECT coalesce(imagen,'-1') as imagen,tipo FROM cugd10_imagenes WHERE  cod_campo=".$opcion." and identificacion='".$id."' and ".$aleatorio."=".$aleatorio);
 	    }
 	    if($cantidad==0){
          $rs_img=$this->cugd10_imagenes->execute("SELECT coalesce(imagen,'-1') as imagen,tipo FROM cugd10_imagenes WHERE  cod_campo=0 and identificacion='0'");
 	    }
 	    $_SESSION["MIME"]=$rs_img[0][0]["tipo"];
  	    $this->set("data_img",pg_unescape_bytea($rs_img[0][0]["imagen"]));
 	}

 	unset($rs_img);


 }//fin ver_miniatura

  function eliminar($id,$opcion) {
  	if($opcion==11 || $opcion==20){
        $cantidad=$this->cugd10_imagenes->findCount("cod_campo=".$opcion."  and identificacion='".$id."'");
	 	if($cantidad!=0){
	         $rs_img=$this->cugd10_imagenes->execute("DELETE FROM cugd10_imagenes WHERE  cod_campo=".$opcion." and identificacion='".$id."'");
	 	}else {
	 		$rs_img =0;
	 	}
  	}
      return $rs_img;
 }//fin eliminar

   function eliminar_imagen($id,$opcion) {
   	$this->layout="ajax";
  	if($opcion==11 || $opcion==20){
        $cantidad=$this->cugd10_imagenes->findCount("cod_campo=".$opcion."  and identificacion='".$id."'");
	 	if($cantidad!=0){
	         $rs_img=$this->cugd10_imagenes->execute("DELETE FROM cugd10_imagenes WHERE  cod_campo=".$opcion." and identificacion='".$id."'");
	 	}else {
	 		$rs_img =0;
	 	}
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

  function ver_miniatura_galeria($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_campo,$identificacion) {
 	$this->layout="images";
 	$aleatorio=intval(rand());
	  	$rs_img=$this->cugd10_imagenes->execute("SELECT coalesce(imagen_miniatura,'-1') as imagen,tipo FROM cugd10_imagenes WHERE  cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst."  and cod_dep=".$cod_dep." and cod_campo=".$cod_campo." and identificacion='".$identificacion."'");
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
