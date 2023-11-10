<?php

class Cnmp06ConstanciaFirmanteController extends AppController {

	var $name = 'cnmp06_constancia_firmante';
 	var $uses = array('ccfd04_cierre_mes','Cnmd01','cnmd06_constancia_firmante','cnmd06_constancia_certificacion','cnmd06_constancia_firmante','v_cnmd06_fichas','v_cnmd06_fichas_datos_personales','cugd02_institucion');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession


 function beforeFilter(){
    $this->checkSession();
 }//fin function

function verifica_SS($i){
			/**
			 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
			 * para ser insertados en todas las tablas.
			 * */
			switch ($i){
				case 1:return $this->Session->read('SScodpresi');break;
				case 2:return $this->Session->read('SScodentidad');break;
				case 3:return $this->Session->read('SScodtipoinst');break;
				case 4:return $this->Session->read('SScodinst');break;
				case 5:return $this->Session->read('SScoddep');break;
				case 6:return $this->Session->read('entidad_federal');break;
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

function condicion(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  return $condicion;
}


	function datos($msj_sw = null) {
		$this->layout="ajax";
		if($msj_sw == "guardados" || $msj_sw == "actualizados"){
			$this->set('Message_existe', 'Datos '.$msj_sw.' con exito!!');
		}else if($msj_sw != null){
			$this->set('Message_existe', $msj_sw);
		}
		$aleatorio=intval(rand());
	  	$datos_img=$this->cnmd06_constancia_firmante->execute("SELECT funcionario_firmante, cargo_firmante, resolucion, tipo_logo_derecho, tipo_logo_izquierdo, tipo_imagen_sello, tipo_imagen_sello_firma, tipo_imagen_firma FROM cnmd06_constancia_firmante WHERE ".$this->SQLCA()." and ".$aleatorio."=".$aleatorio);
	  	$this->set("datos_img", $datos_img);
	}


function guardar_datos($var_mod = null) {
		$this->layout="ajax";
		$funcionario_firmante = $this->data['cnmd06_constancia_firmante']['funcionario_firmante'];
		$cargo_firmante = $this->data['cnmd06_constancia_firmante']['cargo_firmante'];
		$resolucion = $this->data['cnmd06_constancia_firmante']['resolucion'];
		$msj_sw = "";

	if(!empty($funcionario_firmante) && !empty($cargo_firmante) && !empty($resolucion)){

	$cantidad=$this->cnmd06_constancia_firmante->findCount($this->SQLCA());
	if($cantidad==0){
		$msj_sw = "guardados";
		$sql_info = "INSERT INTO cnmd06_constancia_firmante(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,funcionario_firmante,cargo_firmante,resolucion) VALUES (".$this->verifica_SS(1).", ".$this->verifica_SS(2).", ".$this->verifica_SS(3).", ".$this->verifica_SS(4).", ".$this->verifica_SS(5).", '".$funcionario_firmante."', '".$cargo_firmante."', '".$resolucion."');";
	}else{
		$msj_sw = "actualizados";
		$sql_info = "UPDATE cnmd06_constancia_firmante SET funcionario_firmante='".$funcionario_firmante."', cargo_firmante='".$cargo_firmante."', resolucion='".$resolucion."' WHERE ".$this->SQLCA();
	}

	$swi = $this->cnmd06_constancia_firmante->execute($sql_info);
	if($swi>1){
		$this->set('Message_existe', 'Datos '.$msj_sw.' con exito!!');
	}else{
		$this->set('errorMessage', 'Disculpe, El registro no fue guardado!!');
	}

	}else{
		$this->set('errorMessage', 'Debe ingresar el funcionario y cargo del firmante y la resoluci&oacute;n!!');
	}

	// if($var_mod == 'a'){
		$this->redirect('/cnmp06_constancia_firmante/datos/'.$msj_sw);
	// }
}



function modificar_datos() {
	$this->layout="ajax";
	$aleatorio=intval(rand());
	$datos_img=$this->cnmd06_constancia_firmante->execute("SELECT tipo_logo_derecho, tipo_logo_izquierdo, tipo_imagen_sello, tipo_imagen_sello_firma, tipo_imagen_firma FROM cnmd06_constancia_firmante WHERE ".$this->SQLCA()." and ".$aleatorio."=".$aleatorio);
	$this->set("datos_img", $datos_img);
}




function index ($mostrar=null,$opcion=null,$id=null,$id_mostrar_capa=null,$fun_accion=null,$fcroquis=null) {
    $this->layout="ajax";
    $this->set('mostrar',$mostrar);
    if(isset($id) && isset($opcion)){
       $this->Session->write("identificacion",$id);
       $this->set("identificacion",$id);
       $this->set("opcion",$opcion);
       $this->set("ID_CAPAMOSTRAR",$id_mostrar_capa);
       $this->set("funcion_pag",$fun_accion);
       if(isset($fcroquis) && $fcroquis!=null){$this->set('fcroquis',$fcroquis);}
    }
}



 function add($id_capa=null,$opcion=null,$aleatorio=null,$fcroquis=null){
 	if (!empty($this->params['form']) && is_uploaded_file($this->params['form']['File_'.$aleatorio]['tmp_name']) && isset($_SESSION['identificacion']) && isset($opcion)){
 		// if($this->params['form']['File_'.$aleatorio]['type']=="image/jpeg" || $this->params['form']['File_'.$aleatorio]['type']=="image/jpg" || $this->params['form']['File_'.$aleatorio]['type']=="image/png"){

		if($this->params['form']['File_'.$aleatorio]['type']=="image/jpeg"){

           if($this->params['form']['File_'.$aleatorio]['size']<=(4194304*2)){

               if(isset($fcroquis) && $fcroquis!=null){
					$tam_img=500; // 1000
					$tam_img_min=100; // 200
				}else{
					$tam_img=500;
					$tam_img_min=100;
				}
               /*if($opcion==11){
               	 $archivo_nuevo=$this->redimensionar($this->params['form']['File_'.$aleatorio]['tmp_name'],500,0);
               	 $fp=fopen($archivo_nuevo,"rb");
               	 $archivo_miniatura=$this->redimensionar($this->params['form']['File_'.$aleatorio]['tmp_name'],120,0);
               	 $fp_miniatura=fopen($archivo_miniatura,"rb");
               }else{*/
               	 //$fp=fopen($this->params['form']['File_'.$aleatorio]['tmp_name'],"rb");
               	 $archivo_nuevo=$this->redimensionar($this->params['form']['File_'.$aleatorio]['tmp_name'],$tam_img,0);
               	 $fp=fopen($archivo_nuevo,"rb");
               	 $archivo_miniatura=$this->redimensionar($this->params['form']['File_'.$aleatorio]['tmp_name'],$tam_img_min,0);
               	 $fp_miniatura=fopen($archivo_miniatura,"rb");
               //}
                //$fp=fopen($this->params['form']['File_'.$aleatorio]['tmp_name'],"rb");
			    $fileDataOriginal = fread($fp, filesize($archivo_nuevo));
                fclose($fp);
                $fileDataOriginalMiniatura = fread($fp_miniatura, filesize($archivo_miniatura));
                fclose($fp_miniatura);
			    $tipo=$this->params['form']['File_'.$aleatorio]['type'];
			    $size=filesize($archivo_nuevo);
			    $fecha=date("Y-m-d");
			    // $fecha_insert = "1900-01-01";

			    $fecha_insert = $fecha;

			    $fileDataGuardar=pg_escape_bytea($fileDataOriginal);
			    $fileDataGuardarMiniatura=pg_escape_bytea($fileDataOriginalMiniatura);

    			$explo_tipo=explode('image/',$this->params['form']['File_'.$aleatorio]['type']); // Separamos image/ y obtenemos el tipo
    			$tipo_exp=$explo_tipo[1]; // Optenemos el tipo de imagen que es
    			$tipo_cadena=strtolower($tipo_exp);


				switch((string) $opcion){
					case '22' : $img_logo = 'logo_derecho';
								 $tipo_logo = 'tipo_logo_derecho';
								 $size_logo = 'size_logo_derecho';
								 $fecha_logo = 'fecha_logo_derecho';
								 $img_logo_min = 'logo_derecho_miniatura';
								 break;

					case '23' : $img_logo = 'logo_izquierdo';
								 $tipo_logo = 'tipo_logo_izquierdo';
								 $size_logo = 'size_logo_izquierdo';
								 $fecha_logo = 'fecha_logo_izquierdo';
								 $img_logo_min = 'logo_izquierdo_miniatura';
								 break;

					case '24' : $img_logo = 'imagen_sello';
								 $tipo_logo = 'tipo_imagen_sello';
								 $size_logo = 'size_imagen_sello';
								 $fecha_logo = 'fecha_imagen_sello';
								 $img_logo_min = 'imagen_sello_miniatura';
								 break;

					case '25' : $img_logo = 'imagen_sello_firma';
								 $tipo_logo = 'tipo_imagen_sello_firma';
								 $size_logo = 'size_imagen_sello_firma';
								 $fecha_logo = 'fecha_imagen_sello_firma';
								 $img_logo_min = 'imagen_sello_firma_miniatura';
								 break;

					case '26' : $img_logo = 'imagen_firma';
								 $tipo_logo = 'tipo_imagen_firma';
								 $size_logo = 'size_imagen_firma';
								 $fecha_logo = 'fecha_imagen_firma';
								 $img_logo_min = 'imagen_firma_miniatura';
								 break;
				}


		    	$SQL_condicion="cod_presi=".$this->verifica_SS(1)." and cod_entidad=".$this->verifica_SS(2)." and cod_tipo_inst=".$this->verifica_SS(3)." and cod_inst=".$this->verifica_SS(4)." and cod_dep=".$this->verifica_SS(5);
			    $c=$this->cnmd06_constancia_firmante->findCount($SQL_condicion);
				$cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$this->verifica_SS(5);

			    if($c==0){

                    $SQL_INSERT = "INSERT INTO cnmd06_constancia_firmante(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,funcionario_firmante,cargo_firmante,resolucion) VALUES (".$this->verifica_SS(1).", ".$this->verifica_SS(2).", ".$this->verifica_SS(3).", ".$this->verifica_SS(4).", ".$this->verifica_SS(5).", '0', '0', '0');";
					$SQL_M = "UPDATE cnmd06_constancia_firmante SET $img_logo='".$fileDataGuardar."', $tipo_logo='".$tipo."', $size_logo='".$size."', $fecha_logo='".$fecha."', $img_logo_min='".$fileDataGuardarMiniatura."' WHERE ".$SQL_condicion.";";

			        $this->cnmd06_constancia_firmante->execute($SQL_INSERT);
			        $sw_im = $this->cnmd06_constancia_firmante->execute($SQL_M);

			        if($sw_im>1){
			        	$url = $this->themeWeb . IMAGES_URL . "logos_constancia/";
			        	chmod($url, 0777);
			        	move_uploaded_file($this->params['form']['File_'.$aleatorio]['tmp_name'], $url.$img_logo."_".$cod_imagen.".".$tipo_cadena);
			        }


			    }else{
			    	$SQL_INSERT = "";
					$SQL_M = "UPDATE cnmd06_constancia_firmante SET $img_logo='".$fileDataGuardar."', $tipo_logo='".$tipo."', $size_logo='".$size."', $fecha_logo='".$fecha."', $img_logo_min='".$fileDataGuardarMiniatura."' WHERE ".$SQL_condicion.";";
					$sw_im = $this->cnmd06_constancia_firmante->execute($SQL_M);

			        if($sw_im>1){
			        	$url = $this->themeWeb . IMAGES_URL . "logos_constancia/";
			        	 chmod($url, 0777);
			        	move_uploaded_file($this->params['form']['File_'.$aleatorio]['tmp_name'], $url.$img_logo."_".$cod_imagen.".".$tipo_cadena);
			        }

                    // $this->set('errorMessage', 'Ya existe una imagen para este registro');
			    }

                $this->set("identificacion",$this->Session->read('identificacion'));
                $this->set('opcion',$opcion);
                if($fcroquis!=null){$this->set('fcroquis',$fcroquis);}

          unset($fp);
          unset($fp_miniatura);
          unset($fileDataOriginal);
          unset($fileDataOriginalMiniatura);
          unset($fileDataGuardar);
          unset($fileDataGuardarMiniatura);
          unset($SQL_INSERT);
          unset($SQL_M);


		}else{
 			echo "<script>alert('La imagen es muy pesada, supera los 8 Mb\\nImagen Actual de: ".$this->params['form']['File_'.$aleatorio]['size']." Kb');</script>";
 			// echo "<br>Tipo Imagen:".$this->params['form']['File_'.$aleatorio]['type'];
 			// echo "<br>Tamaño: ".$this->params['form']['File_'.$aleatorio]['size'];
 		}


 		}else{
 			echo "<script>alert('Por Favor Cargue una imagen que sea de formato tipo JPG\\nTipo Imagen Actual: ".$this->params['form']['File_'.$aleatorio]['type']." \\nImagen Actual de: ".$this->params['form']['File_'.$aleatorio]['size']." Kb');</script>";
 			// echo "Por Favor Cargue una imagen que sea de formato tipo JPG";
 			// echo "<br>Tipo Imagen:".$this->params['form']['File_'.$aleatorio]['type'];
 			// echo "<br>Tamaño: ".$this->params['form']['File_'.$aleatorio]['size'];
 		}
    }
    $this->set("identificacion",$this->Session->read('identificacion'));
    $this->set('opcion',$opcion);
    $this->set('IDCAPA',$id_capa);
    if($fcroquis!=null){$this->set('fcroquis',$fcroquis);}

}///fin add




 function ver($id,$opcion=null,$var=null,$fcroquis=null) {
 	$this->layout="images";
 	$aleatorio=intval(rand());

				switch((string) $opcion){
					case '22' : $img_logo = 'logo_derecho';
								 $tipo_logo = 'tipo_logo_derecho';
								 $size_logo = 'size_logo_derecho';
								 $fecha_logo = 'fecha_logo_derecho';
								 $img_logo_min = 'logo_derecho_miniatura';
								 break;

					case '23' : $img_logo = 'logo_izquierdo';
								 $tipo_logo = 'tipo_logo_izquierdo';
								 $size_logo = 'size_logo_izquierdo';
								 $fecha_logo = 'fecha_logo_izquierdo';
								 $img_logo_min = 'logo_izquierdo_miniatura';
								 break;

					case '24' : $img_logo = 'imagen_sello';
								 $tipo_logo = 'tipo_imagen_sello';
								 $size_logo = 'size_imagen_sello';
								 $fecha_logo = 'fecha_imagen_sello';
								 $img_logo_min = 'imagen_sello_miniatura';
								 break;

					case '25' : $img_logo = 'imagen_sello_firma';
								 $tipo_logo = 'tipo_imagen_sello_firma';
								 $size_logo = 'size_imagen_sello_firma';
								 $fecha_logo = 'fecha_imagen_sello_firma';
								 $img_logo_min = 'imagen_sello_firma_miniatura';
								 break;

					case '26' : $img_logo = 'imagen_firma';
								 $tipo_logo = 'tipo_imagen_firma';
								 $size_logo = 'size_imagen_firma';
								 $fecha_logo = 'fecha_imagen_firma';
								 $img_logo_min = 'imagen_firma_miniatura';
								 break;
				}


	$cantidad=$this->cnmd06_constancia_firmante->findCount($this->SQLCA());
	if($cantidad!=0){
	  	$rs_img=$this->cnmd06_constancia_firmante->execute("SELECT coalesce($img_logo,'-1') as imagen, $tipo_logo as tipo FROM cnmd06_constancia_firmante WHERE ".$this->SQLCA()." and ".$aleatorio."=".$aleatorio);
	 	$_SESSION["MIME"]=$rs_img[0][0]["tipo"];
	  	$this->set("data_img",pg_unescape_bytea($rs_img[0][0]["imagen"]));
	 	unset($rs_img);
	}
 }//fin ver


 function ver_full($id,$opcion=null,$var=null,$fcroquis=null) {
 	$this->layout="images";
 	$aleatorio=intval(rand());

				switch((string) $opcion){
					case '22' : $img_logo = 'logo_derecho';
								 $tipo_logo = 'tipo_logo_derecho';
								 $size_logo = 'size_logo_derecho';
								 $fecha_logo = 'fecha_logo_derecho';
								 $img_logo_min = 'logo_derecho_miniatura';
								 break;

					case '23' : $img_logo = 'logo_izquierdo';
								 $tipo_logo = 'tipo_logo_izquierdo';
								 $size_logo = 'size_logo_izquierdo';
								 $fecha_logo = 'fecha_logo_izquierdo';
								 $img_logo_min = 'logo_izquierdo_miniatura';
								 break;

					case '24' : $img_logo = 'imagen_sello';
								 $tipo_logo = 'tipo_imagen_sello';
								 $size_logo = 'size_imagen_sello';
								 $fecha_logo = 'fecha_imagen_sello';
								 $img_logo_min = 'imagen_sello_miniatura';
								 break;

					case '25' : $img_logo = 'imagen_sello_firma';
								 $tipo_logo = 'tipo_imagen_sello_firma';
								 $size_logo = 'size_imagen_sello_firma';
								 $fecha_logo = 'fecha_imagen_sello_firma';
								 $img_logo_min = 'imagen_sello_firma_miniatura';
								 break;

					case '26' : $img_logo = 'imagen_firma';
								 $tipo_logo = 'tipo_imagen_firma';
								 $size_logo = 'size_imagen_firma';
								 $fecha_logo = 'fecha_imagen_firma';
								 $img_logo_min = 'imagen_firma_miniatura';
								 break;
				}


	$cantidad=$this->cnmd06_constancia_firmante->findCount($this->SQLCA());
	if($cantidad!=0){
	  	$rs_img=$this->cnmd06_constancia_firmante->execute("SELECT coalesce($img_logo,'-1') as imagen, $tipo_logo as tipo, $size_logo as size, $fecha_logo as fecha, $img_logo_min as imagen_miniatura FROM cnmd06_constancia_firmante WHERE ".$this->SQLCA()." and ".$aleatorio."=".$aleatorio);
	 	$_SESSION["MIME"]=$rs_img[0][0]["tipo"];
	  	$this->set("data_img",pg_unescape_bytea($rs_img[0][0]["imagen"]));
	  	$this->set("tipo",$rs_img[0][0]["tipo"]);
	  	$this->set("size",$rs_img[0][0]["size"]);
	  	$this->set("fecha",$rs_img[0][0]["fecha"]);
	  	$this->set("data_img_min",pg_unescape_bytea($rs_img[0][0]["imagen_miniatura"]));
	 	unset($rs_img);
	}
 }//fin ver



  function ver_miniatura($id,$opcion=null,$var=null) {
 	$this->layout="images";
 	$aleatorio=intval(rand());

				switch((string) $opcion){
					case '22' : $img_logo = 'logo_derecho';
								 $tipo_logo = 'tipo_logo_derecho';
								 $size_logo = 'size_logo_derecho';
								 $fecha_logo = 'fecha_logo_derecho';
								 $img_logo_min = 'logo_derecho_miniatura';
								 break;

					case '23' : $img_logo = 'logo_izquierdo';
								 $tipo_logo = 'tipo_logo_izquierdo';
								 $size_logo = 'size_logo_izquierdo';
								 $fecha_logo = 'fecha_logo_izquierdo';
								 $img_logo_min = 'logo_izquierdo_miniatura';
								 break;

					case '24' : $img_logo = 'imagen_sello';
								 $tipo_logo = 'tipo_imagen_sello';
								 $size_logo = 'size_imagen_sello';
								 $fecha_logo = 'fecha_imagen_sello';
								 $img_logo_min = 'imagen_sello_miniatura';
								 break;

					case '25' : $img_logo = 'imagen_sello_firma';
								 $tipo_logo = 'tipo_imagen_sello_firma';
								 $size_logo = 'size_imagen_sello_firma';
								 $fecha_logo = 'fecha_imagen_sello_firma';
								 $img_logo_min = 'imagen_sello_firma_miniatura';
								 break;

					case '26' : $img_logo = 'imagen_firma';
								 $tipo_logo = 'tipo_imagen_firma';
								 $size_logo = 'size_imagen_firma';
								 $fecha_logo = 'fecha_imagen_firma';
								 $img_logo_min = 'imagen_firma_miniatura';
								 break;
				}


	$cantidad=$this->cnmd06_constancia_firmante->findCount($this->SQLCA());
	if($cantidad!=0){
	  	$rs_img=$this->cnmd06_constancia_firmante->execute("SELECT coalesce($img_logo,'-1') as imagen, $tipo_logo as tipo FROM cnmd06_constancia_firmante WHERE ".$this->SQLCA()." and ".$aleatorio."=".$aleatorio);
	 	$_SESSION["MIME"]=$rs_img[0][0]["tipo"];
	  	$this->set("data_img",pg_unescape_bytea($rs_img[0][0]["imagen"]));
	 	unset($rs_img);
	}
 }//fin ver_miniatura




 function eliminar($id,$opcion) {
	$sw_sql_del = $this->cnmd06_constancia_firmante->execute("DELETE FROM cnmd06_constancia_firmante WHERE ".$this->SQLCA());
  	  if($sw_sql_del>0){
  	  	$msj_sw = 'Registro eliminado';
  	  	$cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$this->verifica_SS(5);

		$url = $this->themeWeb . IMAGES_URL . "logos_constancia/";

			if (file_exists($url."logo_derecho_".$cod_imagen.".jpeg")){
				unlink($url."logo_derecho_".$cod_imagen.".jpeg");
			}else if (file_exists($url."logo_derecho_".$cod_imagen.".jpg")){
				unlink($url."logo_derecho_".$cod_imagen.".jpg");
			}


			if (file_exists($url."logo_izquierdo_".$cod_imagen.".jpeg")){
				unlink($url."logo_izquierdo_".$cod_imagen.".jpeg");
			}else if (file_exists($url."logo_izquierdo_".$cod_imagen.".jpg")){
				unlink($url."logo_izquierdo_".$cod_imagen.".jpg");
			}


			if (file_exists($url."imagen_sello_".$cod_imagen.".jpeg")){
				unlink($url."imagen_sello_".$cod_imagen.".jpeg");
			}else if (file_exists($url."imagen_sello_".$cod_imagen.".jpg")){
				unlink($url."imagen_sello_".$cod_imagen.".jpg");
			}


			if (file_exists($url."imagen_sello_firma_".$cod_imagen.".jpeg")){
				unlink($url."imagen_sello_firma_".$cod_imagen.".jpeg");
			}else if (file_exists($url."imagen_sello_firma_".$cod_imagen.".jpg")){
				unlink($url."imagen_sello_firma_".$cod_imagen.".jpg");
			}


			if (file_exists($url."imagen_firma_".$cod_imagen.".jpeg")){
				unlink($url."imagen_firma_".$cod_imagen.".jpeg");
			}else if (file_exists($url."imagen_firma_".$cod_imagen.".jpg")){
				unlink($url."imagen_firma_".$cod_imagen.".jpg");
			}

  	  }else{
  	  	$msj_sw = 'No se pudo eliminar';
  	  }

	$this->redirect('/cnmp06_constancia_firmante/datos/'.$msj_sw);
 }//fin eliminar



   function eliminar_imagen($id,$opcion) {
   	$this->layout="ajax";

				switch((string) $opcion){
					case '22' : $img_logo = 'logo_derecho';
								 $tipo_logo = 'tipo_logo_derecho';
								 $size_logo = 'size_logo_derecho';
								 $fecha_logo = 'fecha_logo_derecho';
								 $img_logo_min = 'logo_derecho_miniatura';
								 break;

					case '23' : $img_logo = 'logo_izquierdo';
								 $tipo_logo = 'tipo_logo_izquierdo';
								 $size_logo = 'size_logo_izquierdo';
								 $fecha_logo = 'fecha_logo_izquierdo';
								 $img_logo_min = 'logo_izquierdo_miniatura';
								 break;

					case '24' : $img_logo = 'imagen_sello';
								 $tipo_logo = 'tipo_imagen_sello';
								 $size_logo = 'size_imagen_sello';
								 $fecha_logo = 'fecha_imagen_sello';
								 $img_logo_min = 'imagen_sello_miniatura';
								 break;

					case '25' : $img_logo = 'imagen_sello_firma';
								 $tipo_logo = 'tipo_imagen_sello_firma';
								 $size_logo = 'size_imagen_sello_firma';
								 $fecha_logo = 'fecha_imagen_sello_firma';
								 $img_logo_min = 'imagen_sello_firma_miniatura';
								 break;

					case '26' : $img_logo = 'imagen_firma';
								 $tipo_logo = 'tipo_imagen_firma';
								 $size_logo = 'size_imagen_firma';
								 $fecha_logo = 'fecha_imagen_firma';
								 $img_logo_min = 'imagen_firma_miniatura';
								 break;
				}


  		$cantidad=$this->cnmd06_constancia_firmante->findCount($this->SQLCA());
  		$cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$this->verifica_SS(5);
	 	if($cantidad!=0){
			$sql_d = "UPDATE cnmd06_constancia_firmante SET $img_logo=null, $tipo_logo=null, $size_logo=null, $fecha_logo=null, $img_logo_min=null WHERE ".$this->SQLCA();
			$sw_sql_d = $this->cnmd06_constancia_firmante->execute($sql_d);
	 	}else {
			$sw_sql_d = 0;
	 	}

  	  if($sw_sql_d>0){
  	  	$msj_sw = 'La imagen fue eliminada';

		$url = $this->themeWeb . IMAGES_URL . "logos_constancia/";

			if (file_exists($url.$img_logo."_".$cod_imagen.".jpeg")){
				unlink($url.$img_logo."_".$cod_imagen.".jpeg");
			}else if (file_exists($url.$img_logo."_".$cod_imagen.".jpg")){
				unlink($url.$img_logo."_".$cod_imagen.".jpg");
			}

  	  }else{
  	  	$msj_sw = 'La imagen no fue eliminada';
  	  }

	$this->redirect('/cnmp06_constancia_firmante/datos/'.$msj_sw);

 }//fin eliminar



 function ver_imagen_id ($id=null,$opcion=null,$fcroquis=null) {

	$this->layout="ajax";
	if(isset($id) && $id!=null && isset($opcion) && $opcion!=null){
	$this->set("id",$id);
	$this->set('opcion',$opcion);
	}
	if(isset($fcroquis) && $fcroquis!=null){
		$this->set('fcroquis',$fcroquis);
	}
}
 function ver_imagen_grande ($id=null,$opcion=null) {

	$this->layout="ajax";
	if(isset($id) && $id!=null && isset($opcion) && $opcion!=null){
	$this->set("id",$id);
	$this->set('opcion',$opcion);
	}
}

 function ver_img_grande_croquis ($id=null,$opcion=null) {

	$this->layout="ajax";
	if(isset($id) && $id!=null && isset($opcion) && $opcion!=null){
	$this->set("id",$id);
	$this->set('opcion',$opcion);
	}
}


function galeria () {
	$data=$this->cugd10_imagenes->findAll();
	$this->set("dataimg",$data);
}

  function ver_miniatura_galeria($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_campo,$identificacion) {
 	$this->layout="images";
 	$aleatorio=intval(rand());
	  	$rs_img=$this->cugd10_imagenes->execute("SELECT coalesce(imagen_miniatura,'-1') as imagen,tipo FROM cnmd06_constancia_firmante WHERE  cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst."  and cod_dep=".$cod_dep."");
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






/** INICIO CERTIFICACION CONSTANCIA TRABAJO */


function certificacion () {
	$this->layout="ajax";
}

function datos_certificacion ($cod_certif = null) {
	$this->layout="ajax";
	if(!empty($cod_certif)){

	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

		$certificacion = $this->cnmd06_constancia_certificacion->findAll($this->SQLCA()." and UPPER(codigo_certificacion) = UPPER('$cod_certif')", null, null, 1);

		if(!empty($certificacion)){
			$cod_tipo_nomina = $certificacion[0]['cnmd06_constancia_certificacion']['cod_tipo_nomina'];
			$cod_cargo = $certificacion[0]['cnmd06_constancia_certificacion']['cod_cargo'];
			$cod_ficha = $certificacion[0]['cnmd06_constancia_certificacion']['cod_ficha'];
			$cedula_identidad = $certificacion[0]['cnmd06_constancia_certificacion']['cedula_identidad'];
			$codigo_certificacion = $certificacion[0]['cnmd06_constancia_certificacion']['codigo_certificacion'];


	$sueldo = $this->v_cnmd06_fichas->findAll($this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha' and cedula_identidad='$cedula_identidad'",'sueldo_integral', null, 1);
	$datos_constancia = $this->v_cnmd06_fichas_datos_personales->findAll($this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha' and cedula_identidad='$cedula_identidad' and (condicion_actividad=1 OR condicion_actividad=2 OR condicion_actividad=3 OR condicion_actividad=4 OR condicion_actividad=8)",'tipo_nomina, cedula_identidad, nacionalidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, denominacion_clase, fecha_ingreso', null, 1);
	$datos_firma = $this->cnmd06_constancia_firmante->findAll($this->SQLCA(),'funcionario_firmante, cargo_firmante, resolucion', null, 1);


	if($cod_dep != '1'){
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst and cod_dependencia = $cod_dep LIMIT 1;");
	}else{
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst LIMIT 1;");
	}

	$this->set("deno_inst", $deno_inst);
	$this->set("sueldo", $sueldo);
	$this->set("datos_constancia", $datos_constancia);
	$this->set("datos_firma", $datos_firma);
	$this->set("certificacion", $certificacion);

	}else{

			$this->set("certificacion", null);
			$this->set("cod_certificacion", $cod_certif);
			$this->set("errorMessage", "El C&oacute;digo suministrado no es valido!!");
	}

		}else{

			$this->set("certificacion", null);
			$this->set("errorMessage", "Ingrese C&oacute;digo!!");
		}
}




	function codSS ($codigoSS = array(), $ver = true){
                $codigos = explode('-',$codigoSS);
                if(!empty($codigos)){
                    $cod_presi = $codigos[0];
                    $cod_entidad = $codigos[1];
                    $cod_tipo_inst = $codigos[2];
                    $cod_inst = $codigos[3];
                    $cod_dep = $codigos[4];
                    if($ver == true){
                    	if(isset($codigos[5])){
                    		$cod_tipo_nomina = " and cod_tipo_nomina=". $codigos[5];
                    	}else{
                    		$cod_tipo_nomina = "";
                    	}


                    	if(isset($codigos[6])){
                    		$cod_cargo = " and cod_cargo=". $codigos[6];
                    	}else{
                    		$cod_cargo = "";
                    	}


                    	if(isset($codigos[7])){
                    		$cod_ficha = " and cod_ficha=". $codigos[7];
                    	}else{
                    		$cod_ficha = "";
                    	}

                    }else{
                    	$cod_tipo_nomina = "";
                    	$cod_cargo = "";
                    	$cod_ficha = "";
                    }
                }else{
                    $cod_presi = null;
                    $cod_entidad = null;
                    $cod_tipo_inst = null;
                    $cod_inst = null;
                    $cod_dep = null;
                    $cod_tipo_nomina = "";
                    $cod_cargo = "";
                    $cod_ficha = "";
                }

		$condicions = "cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep" . $cod_tipo_nomina . $cod_cargo . $cod_ficha;
		return $condicions;
	}



function const_certificacion(){
	$this->layout = "pdf";
	$cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$this->verifica_SS(5);
	$cod_certificacion = $this->data['cnmd06_constancia']['cod_certificacion'];
	$aleatorio=intval(rand());

	$this->set("cod_imagen", $cod_imagen);

	if(isset($this->data['cnmd06_constancia']['codigo_completo']) && !empty($this->data['cnmd06_constancia']['codigo_completo'])){

	$cod_dep_nomina = $this->data['cnmd06_constancia']['codigo_completo'];
	$cedula_identidad = $this->data['cnmd06_constancia']['ced_identidad'];


	$codigos = explode('-',$cod_dep_nomina);
	if(!empty($codigos)){
		$cod_presi = $codigos[0];
		$cod_entidad = $codigos[1];
		$cod_tipo_inst = $codigos[2];
		$cod_inst = $codigos[3];
		$cod_dep = $codigos[4];
		$cod_tipo_nomina = $codigos[5];
		$cod_cargo = $codigos[6];
		$cod_ficha = $codigos[7];
	}else{
		$cod_presi = null;
		$cod_entidad = null;
		$cod_tipo_inst = null;
		$cod_inst = null;
		$cod_dep = null;
		$cod_tipo_nomina = "";
		$cod_cargo = "";
		$cod_ficha = "";
	}

	if($cod_tipo_inst != '50'){
		$campo_deno = 'deno_cod_secretaria';
	}else{
		$campo_deno = 'deno_cod_direccion';
	}

	$sueldo = $this->v_cnmd06_fichas->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad'",'sueldo_integral,'.$campo_deno, null, 1);
	$datos_constancia = $this->v_cnmd06_fichas_datos_personales->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad' and (condicion_actividad=1 OR condicion_actividad=2 OR condicion_actividad=3 OR condicion_actividad=4 OR condicion_actividad=8)",'tipo_nomina, cedula_identidad, nacionalidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, denominacion_clase, fecha_ingreso', null, 1);
	$datos_firma = $this->cnmd06_constancia_firmante->findAll($this->codSS($cod_dep_nomina, false),'funcionario_firmante, cargo_firmante, resolucion', null, 1);
	$certificacion = $this->cnmd06_constancia_certificacion->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad' and codigo_certificacion='$cod_certificacion'",'cedula_identidad, codigo_certificacion, fecha_emision, fecha_expiracion', null, 1);


	$cod_defecto = $this->cugd02_institucion->execute("SELECT cod_republica, cod_estado, cod_municipio FROM cugd90_municipio_defecto WHERE ".$this->codSS($cod_dep_nomina, false)." LIMIT 1;");

	$republica = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd01_republica WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." LIMIT 1;");
	$estado = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." and cod_estado=".$cod_defecto[0][0]['cod_estado']." LIMIT 1;");
	$cod_zona = $this->cugd02_institucion->execute("SELECT zona_postal FROM cugd01_municipios WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." and cod_estado=".$cod_defecto[0][0]['cod_estado']." and cod_municipio=".$cod_defecto[0][0]['cod_municipio']." LIMIT 1;");


	  	// $rs_img=$this->cnmd06_constancia_firmante->execute("SELECT coalesce(logo_derecho,'-1') as imagen, tipo_logo_derecho as tipo FROM cnmd06_constancia_firmante WHERE ".$this->codSS($cod_dep_nomina, false)." and ".$aleatorio."=".$aleatorio);

  	$datos_imgs=$this->cnmd06_constancia_firmante->execute("SELECT tipo_logo_derecho, tipo_logo_izquierdo, tipo_imagen_sello, tipo_imagen_sello_firma, tipo_imagen_firma FROM cnmd06_constancia_firmante WHERE ".$this->codSS($cod_dep_nomina, false)." and ".$aleatorio."=".$aleatorio);


	if(!empty($certificacion)){

	if($cod_dep != '1'){
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion, direccion, cod_area, telefonos FROM cugd02_dependencias WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst and cod_dependencia = $cod_dep LIMIT 1;");
	}else{
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion, direccion, cod_area, telefonos FROM cugd02_institucion WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst LIMIT 1;");
	}


	$this->set("datos_imgs", $datos_imgs);
	$this->set("cod_tipo_inst", $cod_tipo_inst);
	$this->set("deno_inst", $deno_inst);
	$this->set("cod_zona", $cod_zona);
	$this->set("republica", $republica);
	$this->set("estado", $estado);
	$this->set("sueldo", $sueldo);
	$this->set("datos_constancia", $datos_constancia);
	$this->set("datos_firma", $datos_firma);
	$this->set("certificacion", $certificacion);

	}




	}else{ // NO EXISTE EL CODIGO

		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

		$this->set("cod_certificacion", $cod_certificacion);

	$datos_firma = $this->cnmd06_constancia_firmante->findAll($this->SQLCA(),'funcionario_firmante, cargo_firmante, resolucion', null, 1);

	  	// $rs_img=$this->cnmd06_constancia_firmante->execute("SELECT coalesce(logo_derecho,'-1') as imagen, tipo_logo_derecho as tipo FROM cnmd06_constancia_firmante WHERE ".$this->SQLCA()." and ".$aleatorio."=".$aleatorio);

  	$datos_imgs=$this->cnmd06_constancia_firmante->execute("SELECT tipo_logo_derecho, tipo_logo_izquierdo, tipo_imagen_sello, tipo_imagen_sello_firma, tipo_imagen_firma FROM cnmd06_constancia_firmante WHERE ".$this->SQLCA()." and ".$aleatorio."=".$aleatorio);


	if($cod_dep != '1'){
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion, direccion, cod_area, telefonos FROM cugd02_dependencias WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst and cod_dependencia = $cod_dep LIMIT 1;");
	}else{
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion, direccion, cod_area, telefonos FROM cugd02_institucion WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst LIMIT 1;");
	}


	$this->set("datos_imgs", $datos_imgs);
	$this->set("deno_inst", $deno_inst);
	$this->set("datos_firma", $datos_firma);

	}
}



/** FIN CERTIFICACION CONSTANCIA TRABAJO */








    /**
     * Se carga 4 o 5 imagenes cada una a una columna especfica de la base de datos
     * 1 - logo_derecho
     * 2 - logo_izquierdo
     * 3 - imagen_sello
     * 4 - imagen_sello_firma
     * 5 - imagen_firma
     */
    public function upload($img_opcion = null){
        $this->autoRender = false;
        //echo json_encode($_FILES);
        //$this->autoRender = false;
        //$this->request->onlyAllow('post','put');

        $imagen = $_FILES['imagen'];//$this->request->data['Cpcd02']['imagen'];

        if(($imagen['type']=='image/jpeg') || ($imagen['type']=='image/png')){
            if($imagen['size']<=(4194304*2)){//Aqui podemos operar tranquilamente con la imagen
                //Primero debemos generar la imagen en dos formatos asi que la almacenamos en una carpeta temporal
                $nombre = uniqid();
                $_1000 =$this->proporcion($imagen, 1000, 1000, APP.'tmp_img', $nombre.'_1000');
                $_220 =$this->proporcion($imagen, 220, 220, APP.'tmp_img', $nombre.'_220');

                $ruta_1000 = APP.'tmp_img'.DS.$nombre.'_1000.jpg';
                $ruta_220 = APP.'tmp_img'.DS.$nombre.'_220.jpg';


                if($_1000 && $_220){
                    //Las dos imagenes fueron generadas
                    //Ahora las leemos
                    $data_1000  = file_get_contents($ruta_1000);
                    $image_1000 = pg_escape_bytea($data_1000);
                    //debug($image);
                    $data_220  = file_get_contents($ruta_220);
                    $image_220 = pg_escape_bytea($data_220);

                    $fecha = date('Y-m-d');
                    $size = filesize($ruta_1000);

                    //Actualizamos el campo segun sea la opc
                    switch ($img_opcion) {
                        case '1': //Logo Derecho
                            $SQL_ = "UPDATE cdird02_institucion SET logo_derecho='{$image_1000}' WHERE cod_inst='1'; ";
                            break;
                        case '2': //Logo Izquierdo
                            $SQL_ = "UPDATE cdird02_institucion SET logo_izquierdo='{$image_1000}' WHERE cod_inst='1'; ";
                            break;
                        case '3': //Imagen Sello
                            $SQL_ = "UPDATE cdird02_institucion SET imagen_sello='{$image_1000}' WHERE cod_inst='1'; ";
                            break;
                        case '4': //Imagen Sello Firma
                            $SQL_ = "UPDATE cdird02_institucion SET imagen_sello_firma='{$image_1000}' WHERE cod_inst='1'; ";
                            break;
                        case '5': //Imagen Firma
                            $SQL_ = "UPDATE cdird02_institucion SET imagen_firma='{$image_1000}' WHERE cod_inst='1'; ";
                            break;
                    }
                    //ahora ejecutamos el SQL
                    //Retornamos una respuesta pata que el JS cargue la img cargada
                    echo json_encode(array('completed'=>1,'message'=>'Imagen Cargada','opcion'=>$img_opcion,'rs_query'=>$this->Cdird02Institucion->query($SQL_)));
                }else{
                    echo json_encode(array('completed'=>0,'message'=>'La imagen no pudo ser procesada.'));
                }
                //Al finalizar con el uso de las img, las eliminamos del tmp
                unlink($ruta_1000);
                unlink($ruta_220);
            }else{
                echo json_encode(array('completed'=>0,'message'=>'La imagen es muy pesada, supera los 8 Mb'));
            }
        }else{
            echo json_encode(array('completed'=>0,'message'=>'La imagen no es un formato v&aacute;lido','imagen'=>$imagen));
        }

    }

    /**
     * Esta tabla de este controlador solo trabaja con un registro y si valor es 1
     */
    public function guardar(){
        $this->autoRender = false;
        //Recibo los datos y los alamaceno
        $data = $this->data['Cdird02Institucion'];

        $SQL_ = "UPDATE cdird02_institucion SET nombre_institucion='".$data['nombre_institucion']."', secretaria_direccion='".$data['secretaria_direccion']."', superintendencia='".$data['superintendencia']."', funcionario_firmante='".$data['funcionario_firmante']."', cargo_firmante = '".$data['cargo_firmante']."'    WHERE cod_inst='1'; ";

        $rs = $this->Cdird02Institucion->execute($SQL_);
        if(empty($rs)){
            echo json_encode(array('completed'=>1,'message'=>'Datos Almacenados Correctamente.'));
        }else{
            echo json_encode(array('completed'=>0,'message'=>'Los Datos no fueron almacenados.'));
        }

    }


    /**
     * Trae una imagene en especifico de la intitucion
     * 1 - logo_derecho
     * 2 - logo_izquierdo
     * 3 - imagen_sello
     * 4 - imagen_sello_firma
     * 5 - imagen_firma
     */
    public function imagen($modo=null) {
        //$this->layout = 'img';
        $this->autoRender = false;
        $SQL_ = '';
            switch ($modo) {
                case '1': //Logo Derecho
                    $SQL_ = "SELECT logo_derecho AS imagen FROM cdird02_institucion WHERE cod_inst='1'; ";
                    break;
                case '2': //Logo Izquierdo
                    $SQL_ = "SELECT logo_izquierdo AS imagen FROM cdird02_institucion WHERE cod_inst='1'; ";
                    break;
                case '3': //Imagen Sello
                    $SQL_ = "SELECT imagen_sello AS imagen FROM cdird02_institucion WHERE cod_inst='1'; ";
                    break;
                case '4': //Imagen Sello Firma
                    $SQL_ = "SELECT imagen_sello_firma AS imagen FROM cdird02_institucion WHERE cod_inst='1'; ";
                    break;
                case '5': //Imagen Firma
                    $SQL_ = "SELECT imagen_firma AS imagen FROM cdird02_institucion WHERE cod_inst='1'; ";
                    break;
            }

        $rs=$this->Cdird02Institucion->query($SQL_);

        //print_r($rs);
        //header('Content-type: image/jpeg');
        header('Content-type: image/png');
        //print_r(pg_unescape_bytea($rs[0][0]['imagen']));
        //echo base64_decode($rs[0][0]['imagen']);
        echo pg_unescape_bytea($rs[0][0]['imagen']);
        exit;

    }

    private function proporcion($array_image,$ancho_max,$alto_max,$ruta_destino,$nombre){
                //$ruta_imagen = "imagen_original2.jpg";
                mkdir($ruta_destino, 0775);
                //$dir = new Folder($ruta_destino, true, 0775);
                $ruta_imagen = $array_image['tmp_name'];
                $miniatura_ancho_maximo = ($ancho_max*1);
                $miniatura_alto_maximo = ($alto_max*1);

                $info_imagen = getimagesize($ruta_imagen);
                $imagen_ancho = $info_imagen[0];
                $imagen_alto = $info_imagen[1];
                $imagen_tipo = $info_imagen['mime'];


                $proporcion_imagen = $imagen_ancho / $imagen_alto;
                $proporcion_miniatura = $miniatura_ancho_maximo / $miniatura_alto_maximo;

                if ( $proporcion_imagen > $proporcion_miniatura ){
                        $miniatura_ancho = $miniatura_ancho_maximo;
                        $miniatura_alto = $miniatura_ancho_maximo / $proporcion_imagen;
                } else if ( $proporcion_imagen < $proporcion_miniatura ){
                        $miniatura_ancho = $miniatura_ancho_maximo * $proporcion_imagen;
                        $miniatura_alto = $miniatura_alto_maximo;
                } else {
                        $miniatura_ancho = $miniatura_ancho_maximo;
                        $miniatura_alto = $miniatura_alto_maximo;
                }

                switch ( $imagen_tipo ){
                    case "image/jpg":
                        $imagen = imagecreatefromjpeg( $ruta_imagen );
                        break;
                    case "image/jpeg":
                        $imagen = imagecreatefromjpeg( $ruta_imagen );
                        break;
                    case "image/png":
                        $imagen = imagecreatefrompng( $ruta_imagen );
                        break;
                    case "image/gif":
                        $imagen = imagecreatefromgif( $ruta_imagen );
                        break;
                }
                //Creun un lienzo con el tamano adecuado.
                $lienzo = imagecreatetruecolor( $ancho_max, $alto_max);
                imagefilledrectangle($lienzo, 0, 0, $ancho_max, $alto_max, 0xFFFFFF);//Fondo Blanco

                //Debemos ajustar la pos vertical y horizontal
                $pos_y = ($alto_max - $miniatura_alto)/2;
                $pos_x = ($ancho_max - $miniatura_ancho)/2;

                imagecopyresampled($lienzo, $imagen, $pos_x, $pos_y, 0, 0, $miniatura_ancho, $miniatura_alto, $imagen_ancho, $imagen_alto);

                return imagejpeg($lienzo, $ruta_destino.DS.$nombre.".jpg", 100);
            }






/** CONSTANCIA DE TRABAJO */


function constancia(){
	$this->layout="ajax";

	$lista = $this->Cnmd01->generateList($this->condicion(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');

	if(!empty($lista)){
		$this->concatenaN($lista, 'nominas');
	}else{
		$this->set('nominas', array());
	}
}



function procesa_nomina($vari=null, $cod_nomina=null) {
	$this->layout="ajax";

echo "<script language='JavaScript' type='text/javascript'>
	document.getElementById('cod_nomina').value='';
</script>";

	if($cod_nomina!=null){
		$this->Session->write('codigo_tipo_nomina', $cod_nomina);
		if($vari=='1'){

				$url                  =  "/cnmp06_constancia_firmante/buscar_datos_personales/$vari/$cod_nomina";
				$width_aux            =  "750px";
				$height_aux           =  "400px";
				$title_aux            =  "Buscar";
				$resizable_aux        =  false;
				$maximizable_aux      =  false;
				$minimizable_aux      =  false;
				$closable_aux         =  false;

			 echo "<script>";
	           echo "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo "</script>";
		}else{
			$this->set('errorMessage','No se pudo efectuar el proceso!!');
		}
	}else{
		$this->Session->write('codigo_tipo_nomina', '0');
		$this->set('errorMessage','Seleccione la n&oacute;mina!!');
	}
} // fin funcion


function buscar_datos_personales($var1=null, $cod=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	echo "<script>$('select_obra_cod_obra').focus();</script>";
}//fin function


function buscar_datos_porpista($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$modelo='v_cnmd06_fichas';
	$cod_nomina = $this->Session->read('codigo_tipo_nomina');
    if($var3==null){ $var2 = strtoupper($var2);
					 $this->Session->write('pista', $var2);
					 $Tfilas=$this->$modelo->findCount($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and (condicion_actividad_ficha=1 OR condicion_actividad_ficha=2 OR condicion_actividad_ficha=3 OR condicion_actividad_ficha=4 OR condicion_actividad_ficha=8) and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								$datos_filas=$this->$modelo->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and (condicion_actividad_ficha=1 OR condicion_actividad_ficha=2 OR condicion_actividad_ficha=3 OR condicion_actividad_ficha=4 OR condicion_actividad_ficha=8) and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))", "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido", "cedula_identidad, primer_nombre ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
								$this->set('total_paginas','');
								$this->set('pagina_actual','');
							    $this->set('siguiente','');
								$this->set('anterior','');
								$this->set('ultimo','');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->$modelo->findCount($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and (condicion_actividad_ficha=1 OR condicion_actividad_ficha=2 OR condicion_actividad_ficha=3 OR condicion_actividad_ficha=4 OR condicion_actividad_ficha=8) and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->$modelo->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and (condicion_actividad_ficha=1 OR condicion_actividad_ficha=2 OR condicion_actividad_ficha=3 OR condicion_actividad_ficha=4 OR condicion_actividad_ficha=8) and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))", "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido", "cedula_identidad, primer_nombre ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
									$this->set('total_paginas','');
									$this->set('pagina_actual','');
							    	$this->set('siguiente','');
									$this->set('anterior','');
									$this->set('ultimo','');
						          }
   		}//fin else
$this->set("opcion",$var1);
$this->set("cod_nomi",$cod_nomina);
} //fin funcion


function procesar($opc = null, $cod_dep_nomina = null, $cedula_identidad = null){
	$this->layout="ajax";
	$aleatorio=intval(rand());

	$codigos = explode('-',$cod_dep_nomina);
	if(!empty($codigos)){
		$cod_presi = $codigos[0];
		$cod_entidad = $codigos[1];
		$cod_tipo_inst = $codigos[2];
		$cod_inst = $codigos[3];
		$cod_dep = $codigos[4];
		$cod_tipo_nomina = $codigos[5];
		$cod_cargo = $codigos[6];
		$cod_ficha = $codigos[7];
	}else{
		$cod_presi = null;
		$cod_entidad = null;
		$cod_tipo_inst = null;
		$cod_inst = null;
		$cod_dep = null;
		$cod_tipo_nomina = "";
		$cod_cargo = "";
		$cod_ficha = "";
	}

	$datos_perso = $this->v_cnmd06_fichas->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad'",'cod_tipo_nomina, cod_ficha, cedula_identidad, nacionalidad, sexo, estado_civil', null, 1);

// Codigos para el cod certificacion:
	$cedula_s1 = substr($datos_perso[0]['v_cnmd06_fichas']['cedula_identidad'], -4, 4);
	$cedula_s2 = substr($datos_perso[0]['v_cnmd06_fichas']['cedula_identidad'], 0, 2);
	$aleatorio = substr($aleatorio, 0, 2);
	$segun = date("s");
	$mes_act = $this->mes_cod(date("m"));
	$ano_act = substr(date("Y"), -2, 2);
	$num_a1 = $this->num_aleatorio($datos_perso[0]['v_cnmd06_fichas']['cedula_identidad']);
	$num_a2 = $this->num_aleatorio($datos_perso[0]['v_cnmd06_fichas']['cedula_identidad']);
// Fin codigos

	$codigo_certificacion = $num_a1.$datos_perso[0]['v_cnmd06_fichas']['sexo'].$cedula_s1.$datos_perso[0]['v_cnmd06_fichas']['estado_civil'].mascara($datos_perso[0]['v_cnmd06_fichas']['cod_ficha'], 2).$datos_perso[0]['v_cnmd06_fichas']['nacionalidad'].$cedula_s2.$datos_perso[0]['v_cnmd06_fichas']['cod_tipo_nomina'].$aleatorio.$num_a2.$mes_act.$ano_act.$segun;
	$fecha_emision = date("Y-m-d");
	$mes = date("m");

	if($mes!='12'){
		$mes = $mes + 1;
		$mes = mascara($mes, 2);
		if($mes==2 AND date("d")>=28){
			$fecha_expiracion = date("Y")."-".$mes."-"."28";
		}elseif ($mes!=2 AND date("d")==31) {
			$fecha_expiracion = date("Y")."-".$mes."-"."30";
		}else{
			$fecha_expiracion = date("Y")."-".$mes."-".date("d");
		}

	}else{
		$fecha_expiracion = date("Y")."-".$mes."-31";
	}


	/*
	$certificacion = $this->cnmd06_constancia_certificacion->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad'", 'codigo_certificacion', null, 1);
	if(!empty($certificacion)){
	}
	*/

	$fecha_exp = $this->cnmd06_constancia_certificacion->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad'",'codigo_certificacion, fecha_expiracion', null, 1);

	if(!empty($fecha_exp)){

		$fecha = $fecha_exp[0]['cnmd06_constancia_certificacion']['fecha_expiracion'];
            $yearf = $fecha[0] . $fecha[1] . $fecha[2] . $fecha[3];
            $mesf = $fecha[5] . $fecha[6];
            $diaf = $fecha[8] . $fecha[9];

	$fecha_expira = $diaf."-".$mesf."-".$yearf;
	$fecha_dia = date("d-m-Y");

		if ($this->compara_fechas($fecha_dia,$fecha_expira) >0){ // Si Expira o caduca el codigo de certificacion

				$this->cnmd06_constancia_certificacion->execute("DELETE FROM cnmd06_constancia_certificacion WHERE ".$this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad';");
				$sw_c = $this->cnmd06_constancia_certificacion->execute("INSERT INTO cnmd06_constancia_certificacion VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina, $cod_cargo, $cod_ficha, '$cedula_identidad', '".$codigo_certificacion."', '$fecha_emision', '$fecha_expiracion');");
		}else{
			$codigo_certificacion = $fecha_exp[0]['cnmd06_constancia_certificacion']['codigo_certificacion'];
			$sw_c = $this->cnmd06_constancia_certificacion->execute("UPDATE cnmd06_constancia_certificacion SET fecha_emision='$fecha_emision', fecha_expiracion='$fecha_expiracion' WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad;");
		}
	}else{

		$this->cnmd06_constancia_certificacion->execute("DELETE FROM cnmd06_constancia_certificacion WHERE ".$this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad';");
		$sw_c = $this->cnmd06_constancia_certificacion->execute("INSERT INTO cnmd06_constancia_certificacion VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina, $cod_cargo, $cod_ficha, '$cedula_identidad', '".$codigo_certificacion."', '$fecha_emision', '$fecha_expiracion');");
	}

	if($sw_c>1){
		$this->set("procesado", true);

/*
		echo "<script language='JavaScript' type='text/javascript'>
	ver_documento('/cnmp06_constancia_firmante/datos_certificacion/$codigo_certificacion', 'carga_datos');
</script>";
*/

	}else{
		$this->set("procesado", false);
	}

	$this->set("cod_dep_nomina", $cod_dep_nomina);
	$this->set("cedula_identidad", $cedula_identidad);
	$this->set("codigo_certificacion", $codigo_certificacion);
}


function datos_certificacion_proc ($cod_certif = null) {
	$this->layout="ajax";
	if(!empty($cod_certif)){

	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

		$certificacion = $this->cnmd06_constancia_certificacion->findAll($this->SQLCA()." and UPPER(codigo_certificacion) = UPPER('$cod_certif')", null, null, 1);

		if(!empty($certificacion)){
			$cod_tipo_nomina = $certificacion[0]['cnmd06_constancia_certificacion']['cod_tipo_nomina'];
			$cod_cargo = $certificacion[0]['cnmd06_constancia_certificacion']['cod_cargo'];
			$cod_ficha = $certificacion[0]['cnmd06_constancia_certificacion']['cod_ficha'];
			$cedula_identidad = $certificacion[0]['cnmd06_constancia_certificacion']['cedula_identidad'];
			$codigo_certificacion = $certificacion[0]['cnmd06_constancia_certificacion']['codigo_certificacion'];


	$sueldo = $this->v_cnmd06_fichas->findAll($this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha' and cedula_identidad='$cedula_identidad'",'sueldo_integral', null, 1);
	$datos_constancia = $this->v_cnmd06_fichas_datos_personales->findAll($this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha' and cedula_identidad='$cedula_identidad' and (condicion_actividad=1 OR condicion_actividad=2 OR condicion_actividad=3 OR condicion_actividad=4 OR condicion_actividad=8)",'tipo_nomina, cedula_identidad, nacionalidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, denominacion_clase, fecha_ingreso', null, 1);
	$datos_firma = $this->cnmd06_constancia_firmante->findAll($this->SQLCA(),'funcionario_firmante, cargo_firmante, resolucion', null, 1);


	if($cod_dep != '1'){
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst and cod_dependencia = $cod_dep LIMIT 1;");
	}else{
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst LIMIT 1;");
	}

	$this->set("deno_inst", $deno_inst);
	$this->set("sueldo", $sueldo);
	$this->set("datos_constancia", $datos_constancia);
	$this->set("datos_firma", $datos_firma);
	$this->set("certificacion", $certificacion);

	}else{

			$this->set("certificacion", null);
			$this->set("cod_certificacion", $cod_certif);
			$this->set("errorMessage", "El C&oacute;digo suministrado no es valido!!");
	}

		}else{

			$this->set("certificacion", null);
			$this->set("errorMessage", "Ingrese C&oacute;digo!!");
		}
}


function constancia_trabajo(){
	$this->layout = "pdf";
	$cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$this->verifica_SS(5);
	$cod_dep_nomina = $this->data['cnmd06_constancia']['codigo_completo'];
	$cedula_identidad = $this->data['cnmd06_constancia']['ced_identidad'];
	$aleatorio=intval(rand());

	$codigos = explode('-',$cod_dep_nomina);
	if(!empty($codigos)){
		$cod_presi = $codigos[0];
		$cod_entidad = $codigos[1];
		$cod_tipo_inst = $codigos[2];
		$cod_inst = $codigos[3];
		$cod_dep = $codigos[4];
		$cod_tipo_nomina = $codigos[5];
		$cod_cargo = $codigos[6];
		$cod_ficha = $codigos[7];
	}else{
		$cod_presi = null;
		$cod_entidad = null;
		$cod_tipo_inst = null;
		$cod_inst = null;
		$cod_dep = null;
		$cod_tipo_nomina = "";
		$cod_cargo = "";
		$cod_ficha = "";
	}

	if($cod_tipo_inst != '50'){
		$campo_deno = 'deno_cod_secretaria';
	}else{
		$campo_deno = 'deno_cod_direccion';
	}


	$sueldo = $this->v_cnmd06_fichas->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad'",'sueldo_integral,'.$campo_deno, null, 1);
	$datos_constancia = $this->v_cnmd06_fichas_datos_personales->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad' and (condicion_actividad=1 OR condicion_actividad=2 OR condicion_actividad=3 OR condicion_actividad=4 OR condicion_actividad=8)",'tipo_nomina, cedula_identidad, nacionalidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, denominacion_clase, fecha_ingreso, sexo', null, 1);
	$datos_firma = $this->cnmd06_constancia_firmante->findAll($this->codSS($cod_dep_nomina, false),'funcionario_firmante, cargo_firmante, resolucion', null, 1);
	$certificacion = $this->cnmd06_constancia_certificacion->findAll($this->codSS($cod_dep_nomina)." and cedula_identidad='$cedula_identidad'",'cedula_identidad, codigo_certificacion, fecha_emision, fecha_expiracion', null, 1);

	if($cod_dep != '1'){
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion, direccion, cod_area, telefonos FROM cugd02_dependencias WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst and cod_dependencia = $cod_dep LIMIT 1;");
	}else{
		$deno_inst = $this->cugd02_institucion->execute("SELECT denominacion, direccion, cod_area, telefonos FROM cugd02_institucion WHERE cod_tipo_institucion = $cod_tipo_inst and cod_institucion = $cod_inst LIMIT 1;");
	}


	$cod_defecto = $this->cugd02_institucion->execute("SELECT cod_republica, cod_estado, cod_municipio FROM cugd90_municipio_defecto WHERE ".$this->codSS($cod_dep_nomina, false)." LIMIT 1;");

	$republica = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd01_republica WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." LIMIT 1;");
	$estado = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." and cod_estado=".$cod_defecto[0][0]['cod_estado']." LIMIT 1;");
	$cod_zona = $this->cugd02_institucion->execute("SELECT conocido, zona_postal FROM cugd01_municipios WHERE cod_republica=".$cod_defecto[0][0]['cod_republica']." and cod_estado=".$cod_defecto[0][0]['cod_estado']." and cod_municipio=".$cod_defecto[0][0]['cod_municipio']." LIMIT 1;");



	  	// $rs_img=$this->cnmd06_constancia_firmante->execute("SELECT coalesce(logo_derecho,'-1') as imagen, tipo_logo_derecho as tipo FROM cnmd06_constancia_firmante WHERE ".$this->codSS($cod_dep_nomina, false)." and ".$aleatorio."=".$aleatorio);


  	$datos_imgs=$this->cnmd06_constancia_firmante->execute("SELECT tipo_logo_derecho, tipo_logo_izquierdo, tipo_imagen_sello, tipo_imagen_sello_firma, tipo_imagen_firma FROM cnmd06_constancia_firmante WHERE ".$this->codSS($cod_dep_nomina, false)." and ".$aleatorio."=".$aleatorio);

	$this->set("cod_imagen", $cod_imagen);
  	$this->set("datos_imgs", $datos_imgs);
	$this->set("cod_tipo_inst", $cod_tipo_inst);
	$this->set("deno_inst", $deno_inst);
	$this->set("cod_zona", $cod_zona);
	$this->set("republica", $republica);
	$this->set("estado", $estado);
	$this->set("sueldo", $sueldo);
	$this->set("datos_constancia", $datos_constancia);
	$this->set("datos_firma", $datos_firma);
	$this->set("certificacion", $certificacion);
	$this->set("cod_dep", $cod_dep);
}





// Devuelve los dias exactos entre dos fechas:

function calcula_dias_fecha($fecha_row, $fecha_actual){
	$segundos=strtotime($fecha_row) - strtotime($fecha_actual);
	$diferencia_dias=intval($segundos/60/60/24);
	return $diferencia_dias;
}



function compara_fechas($fecha1,$fecha2){
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))
              list($dia1,$mes1,$año1)=split("/",$fecha1);
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))
              list($dia1,$mes1,$año1)=split("-",$fecha1);
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))
              list($dia2,$mes2,$año2)=split("/",$fecha2);
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))
              list($dia2,$mes2,$año2)=split("-",$fecha2);
        $dif = mktime(0,0,0,$mes1,$dia1,$año1) - mktime(0,0,0, $mes2,$dia2,$año2);
        return ($dif);
}//fin function


// Funcion para calcular numero entero aleatorio de una cadena, donde n: es el rango max
function num_aleatorio($cadena=null){
	if($cadena!=null){
		$n = strlen($cadena);
	}else{
		$n=8;
	}

    for($i=0;$i<$n;$i++){
        $numero[$i] = ($i+1);
    }
    $get = count($numero)-1;
    $aleatoreo = rand(0,$get);
    return $aleatoreo;
}



// Funcion para calcular numero entero aleatorio directo, donde n: es el rango max
function num_aleatorio2($n=8){
    for($i=0;$i<$n;$i++){
        $numero[$i] = ($i+1);
    }
    $get = count($numero)-1;
    $aleatoreo = rand(0,$get);
    return $aleatoreo;
}


    function mes_cod($value_mes = null) {
        if (empty($value_mes)) {
            $value_mes = date("m");
        }

        $meses_cod = array('01' => 'E1', '02' => 'F2', '03' => 'M3', '04' => 'A4', '05' => 'M5', '06' => 'J6', '07' => 'J7', '08' => 'A8', '09' => 'S9', '10' => 'O0', '11' => 'N1', '12' => 'D2');
		$valor_cod = $meses_cod[$value_mes];
		if(empty($valor_cod)){
			$valor_cod = "Z0";
		}

        return $valor_cod;
    }


} // Fin class

?>
