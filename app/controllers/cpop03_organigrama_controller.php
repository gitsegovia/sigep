<?php
 class Cpop03OrganigramaController extends AppController {
 	var $name = 'cpop03_organigrama';
 	var $uses = array('cpod03_organigrama', 'ccfd04_cierre_mes', 'cfpd01_formulacion','cnmd06_constancia_firmante');
 	var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap', 'Fpdf','Form','Fck');



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

		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$modulo = $this->Session->read('Modulo');

		return;
	}

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
			default: return "NULO";
		}
	}

	function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
		$sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
		$sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
		$sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
		$sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
		$sql_re .= "cod_dep=".$this->verifica_SS(5). " ";

		if($ano!=null){
			$sql_re .= "and ano=" . $ano ." ";
		}

		return $sql_re;
	}

	function SQLCA_Institucion($ano=null){//sql para busqueda de codigos de arranque con y sin año
		$sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
		$sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
		$sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
		$sql_re .= "cod_inst=".$this->verifica_SS(4)." ";

		if($ano!=null){
			$sql_re .= " and ano=".$ano."  ";
		}
		return $sql_re;
	}


/**
 *  Informacion de la tabla cpod03_organigrama
(
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  ano integer NOT NULL,
  fundamento_legal character varying(100),
  organigrama bytea,
  tipo_organigrama character varying(100),
  size_organigrama integer,
  fecha_organigrama date,
  organigrama_miniatura bytea,
 */

	/*****
	 **  ACCIONES
	 **/

	function index($ano='') {

 		$this->layout = "ajax";

		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

		$year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

  	if($ano==null){
			$ano_formular=$year[0]['cfpd01_formulacion']['ano_formular'];
			$this->set('consulta',false);
		}elseif($ano==$year[0]['cfpd01_formulacion']['ano_formular']){
			$ano_formular=$ano;
			$this->set('consulta',false);
		}else{
			$ano_formular=$ano;
			$this->set('consulta',true);
		}

	  	//$datos_img=$this->cpod03_organigrama->execute("SELECT ano_formulacion, fundamento_legal, coalesce('organigrama','-1') as imagen, tipo_organigrama as tipo FROM cpod03_organigrama WHERE ".$this->SQLCA($ano));
	  	$datos_img = $this->cpod03_organigrama->find($this->SQLCA($ano_formular),"ano, fundamento_legal, coalesce(organigrama,'-1') as imagen, tipo_organigrama");
	  	$this->set("datos_img", $datos_img);
		$this->set("ano", $ano_formular);
		$this->set("cod_dep", $cod_dep);
	  	$this->Session->write("ano_cargar_imagen",$ano_formular);

  	}

  	//$mostrar         = accion a realizar en subir imagen
  	//$id_mostrar_capa = id del div donde se cargara la imagen
  	//$fun_accion      = accion q sera llamada desde la carga de la imagen
  function subir_imagen($mostrar=null,$id_mostrar_capa=null,$fun_accion=null,$id=null) {
   	$this->layout="ajax";
   	$this->set('mostrar',$mostrar);
	  if(isset($id)){
	    $this->Session->write("identificacion",$id);
	    $this->set("identificacion",$id);
	    $this->set("ID_CAPAMOSTRAR",$id_mostrar_capa);
	    $this->set("funcion_pag",$fun_accion);
	  }
	}

	function add($id_capa,$aleatorio){

		$tam_img     = 450;
		$tam_img_min = 350;
		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
 		
		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){

	 		if (!empty($this->params['form']) && is_uploaded_file($this->params['form']['File_'.$aleatorio]['tmp_name']) && isset($_SESSION['identificacion'])){

	 			if($this->params['form']['File_'.$aleatorio]['type']=="image/jpeg"){

					if($this->params['form']['File_'.$aleatorio]['size']<=(4194304*2)){

							$archivo_nuevo=$this->redimensionar($this->params['form']['File_'.$aleatorio]['tmp_name'],$tam_img,0);

							$fp=fopen($archivo_nuevo,"rb");

							$archivo_miniatura=$this->redimensionar($this->params['form']['File_'.$aleatorio]['tmp_name'],$tam_img_min,0);

							$fp_miniatura=fopen($archivo_miniatura,"rb");

							$fileDataOriginal = fread($fp, filesize($archivo_nuevo));
							fclose($fp);

							$fileDataOriginalMiniatura = fread($fp_miniatura, filesize($archivo_miniatura));
							fclose($fp_miniatura);

							$tipo=$this->params['form']['File_'.$aleatorio]['type'];
							$size=filesize($archivo_nuevo);
							$fecha=date("Y-m-d");

							$fecha_insert = $fecha;

					    $fileDataGuardar=pg_escape_bytea($fileDataOriginal);
					    $fileDataGuardarMiniatura=pg_escape_bytea($fileDataOriginalMiniatura);

	    				$explo_tipo=explode('image/',$this->params['form']['File_'.$aleatorio]['type']); // Separamos image/ y obtenemos el tipo
	    				$tipo_exp=$explo_tipo[1]; // Optenemos el tipo de imagen que es
	    				$tipo_cadena=strtolower($tipo_exp);

					    if($this->cpod03_organigrama->findCount($this->SQLCA($_SESSION['ano_cargar_imagen']))==0){

	                $SQL_INSERT = "INSERT INTO cpod03_organigrama(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano, fundamento_legal) VALUES (".$this->verifica_SS(1).", ".$this->verifica_SS(2).", ".$this->verifica_SS(3).", ".$this->verifica_SS(4).", ".$this->verifica_SS(5).", ".$_SESSION['ano_cargar_imagen'].", '');";
									$SQL_M = "UPDATE cpod03_organigrama SET organigrama='".$fileDataGuardar."', tipo_organigrama='".$tipo."', size_organigrama='".$size."', fecha_organigrama='".$fecha."', organigrama_miniatura='".$fileDataGuardarMiniatura."' WHERE ".$this->SQLCA($_SESSION['ano_cargar_imagen']).";";

				        	$this->cpod03_organigrama->execute($SQL_INSERT);
				        	$sw_im = $this->cpod03_organigrama->execute($SQL_M);

					        /*
					         * CARGAR LA IMAGEN EN UNA CARPETA EN EL SERVIDOR webroot/img
					         *
					         */
					        if($sw_im>1){
					        	$url = $this->themeWeb . IMAGES_URL . "organigramas_poai/";
					        	$cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$this->verifica_SS(5);
					        	chmod($url, 0777);
					        	move_uploaded_file($this->params['form']['File_'.$aleatorio]['tmp_name'], $url."organigrama_".$_SESSION['ano_cargar_imagen']."_".$cod_imagen.".".$tipo_cadena);
					        }

					    }else{
					    	$SQL_INSERT = "";
								$SQL_M = "UPDATE cpod03_organigrama SET organigrama='".$fileDataGuardar."', tipo_organigrama='".$tipo."', size_organigrama='".$size."', fecha_organigrama='".$fecha."', organigrama_miniatura='".$fileDataGuardarMiniatura."' WHERE ".$this->SQLCA($_SESSION['ano_cargar_imagen']).";";
								$sw_im = $this->cpod03_organigrama->execute($SQL_M);

				        	/*
					         * CARGAR LA IMAGEN EN UNA CARPETA EN EL SERVIDOR webroot/img
					         *
					         */
					        if($sw_im>1){
					        	$url = $this->themeWeb . IMAGES_URL . "organigramas_poai/";
					        	$cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$this->verifica_SS(5);
					        	chmod($url, 0777);
					        	move_uploaded_file($this->params['form']['File_'.$aleatorio]['tmp_name'], $url."organigrama_".$_SESSION['ano_cargar_imagen']."_".$cod_imagen.".".$tipo_cadena);
					        }

	                    	// $this->set('errorMessage', 'Ya existe una imagen para este registro');
				    	}

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
	 				}

		 		}else{
	 				echo "<script>alert('Por Favor Cargue una imagen que sea de formato tipo JPG\\nTipo Imagen Actual: ".$this->params['form']['File_'.$aleatorio]['type']."');</script>";
	 			}

		  }

    $this->set("IDCAPA", $id_capa);
    $this->set("aleatorio",$aleatorio);

		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
		}

	}///fin add

	function ver_imagen($aleatorio) {
		$this->layout="ajax";
		$this->set('aleatorio',$aleatorio);
		$this->set("id", $_SESSION['identificacion']);
	}

  function ver_miniatura($ano, $aleatorio) {

	 	$this->layout="images";

 		$cantidad=$this->cpod03_organigrama->findCount($this->SQLCA($ano));
		if($cantidad!=0){
		  	$rs_img=$this->cpod03_organigrama->execute("SELECT coalesce(organigrama,'-1') as imagen, tipo_organigrama as tipo FROM cpod03_organigrama WHERE ".$this->SQLCA($ano)." and ".$aleatorio."=".$aleatorio);
		 	$_SESSION["MIME"]=$rs_img[0][0]["tipo"];
		  	$this->set("data_img",pg_unescape_bytea($rs_img[0][0]["imagen"]));
		 	unset($rs_img);
		}
 	}//fin ver_miniatura


	function guardar_datos($var_mod = null) {
		$this->layout="ajax";
		$fundamento_legal = $this->data['cpod03_organigrama']['fundamento_legal'];
		$msj_sw = "";
		
		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
 		
		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
		
			if(!empty($fundamento_legal)){

				$cantidad=$this->cpod03_organigrama->findCount($this->SQLCA($_SESSION['ano_cargar_imagen']));
				if($cantidad==0){

					$msj_sw = "guardados";
					$sql_info = "INSERT INTO cpod03_organigrama(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,fundamento_legal) VALUES (".$this->verifica_SS(1).", ".$this->verifica_SS(2).", ".$this->verifica_SS(3).", ".$this->verifica_SS(4).", ".$this->verifica_SS(5).", '".$_SESSION['ano_cargar_imagen']."', '".$fundamento_legal."');";

				}else{

					$msj_sw = "actualizados";
					$sql_info = "UPDATE cpod03_organigrama SET fundamento_legal='".$fundamento_legal."' WHERE ".$this->SQLCA($_SESSION['ano_cargar_imagen']);

				}

				if($this->cpod03_organigrama->execute($sql_info)>1){
					$this->set('Message_existe', 'Datos '.$msj_sw.' con exito!!');
				}else{
					$this->set('errorMessage', 'Disculpe, El registro no fue guardado.');
				}

			}else{
				$this->set('errorMessage', 'Debe ingresar el fundamento legal de aprobación del organigrama.');
			}

		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
		}

		$this->index();
		$this->render('index');

	}

 	function eliminar(){

		$this->layout="ajax";

 		$year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
 		$ano = $year[0]['cfpd01_formulacion']['ano_formular'];
 		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
 		
		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){

			if($this->cpod03_organigrama->execute("DELETE FROM cpod03_organigrama WHERE " . $this->SQLCA($ano))>1){

				$cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$this->verifica_SS(5);

				$url = IMAGES_URL . "organigramas_poai/";
				
				if(file_exists($url."organigrama_".$ano."_".$cod_imagen.".jpeg")){
					unlink($url."organigrama_".$ano."_".$cod_imagen.".jpeg");
				}else if (file_exists($url."organigrama_".$ano."_".$cod_imagen.".jpg")){
					unlink($url."organigrama_".$ano."_".$cod_imagen.".jpg");
				}

				$this->set('Message_existe','Registro Eliminado con Exito');
			}else{
				$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
			}

		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
		}

		$this->index();
		$this->render('index');
	}


 	

	function modificar() {

		$this->layout="ajax";

		$activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
 		
		if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
			$this->set('editar',true);
		}else{
			$this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
		}

		$this->index();
		$this->render('index');
 		

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

 }
?>
