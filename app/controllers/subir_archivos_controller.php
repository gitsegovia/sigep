<?php



class SubirArchivosController extends AppController {

	var $name = 'SubirArchivos';
	var $uses = null;
	var $helpers = array('Html','Ajax','Javascript');


	function index () {
		$this->layout="ajax";
	}//end index

	function guardar($aleatorio=null){
    $this->layout="ajax";
    //ini_set("memory_limit","256M");
    //ini_set("upload_max_filesize","100M");
    //ini_set("post_max_size","100M");
    // pr($this->params['form']);
 	if (!empty($this->params['form']) && is_uploaded_file($this->params['form']['File']['tmp_name'])){
             $fileOK = $this->uploadFiles('files', $this->params['form']['File']);
			// si el archivo fue subido correctamente
			if(array_key_exists('urls', $fileOK)) {
			// guardar la url en la informacion del from
			echo $fileOK['urls'][0];
			    $archivo = file($fileOK['urls'][0]);
				$lineas = count($archivo);
					for($i=0; $i < $lineas; $i++){
				            $campos = explode(';',$archivo[$i]);
				            $c_campos = count($campos);
				            if($c_campos!=1 && $c_campos==21){
				                for($j=0; $j<$c_campos;$j++){
				                   $campos_aux[$j] = "'".str_replace('"','',$campos[$j])."'";
				                }
				                echo "<br>(".implode(',',$campos_aux).")";
				            }//)*/
					    echo "<br/>[$i] <- ".$archivo[$i] ." campos[".$c_campos."]" ;
					}
			 unlink($fileOK['urls'][0]);
			}//fin file ok
			//pr($fileOK);
			//echo "HOLA jose";
    }
    //echo "HOLA";
}///fin guardar

	function uploadFiles($folder, $formdata, $itemId = null) {
	// setup dir names absolute and relative
	$folder_url = WWW_ROOT.$folder;
	$rel_url = $folder;

	// create the folder if it does not exist
	if(!is_dir($folder_url)) {
	mkdir($folder_url);
	}

	// if itemId is set create an item folder
	if($itemId) {
	// set new absolute folder
	$folder_url = WWW_ROOT.$folder.'/'.$itemId;
	// set new relative folder
	$rel_url = $folder.'/'.$itemId;
	// create directory
	if(!is_dir($folder_url)) {
	mkdir($folder_url);
	}
	}

	// list of permitted file types, this is only images but documents can be added
	//$permitted = array('image/gif','image/jpeg','image/pjpeg','image/png');
	$permitted = array('text/plain');

	// loop through and deal with the files
	$file = $formdata;
//	foreach($formdata as $file) {
		// replace spaces with underscores
		$filename = str_replace(' ', '_', $file['name']);
		// assume filetype is false
		$typeOK = false;
		// check filetype is ok
		foreach($permitted as $type) {
			// echo $file['type'];
		if($type == $file['type']) {
		$typeOK = true;
		break;
		}
		}

		// if file type ok upload the file
		if($typeOK) {
		// switch based on error code
		switch($file['error']) {
		case 0:
		// check filename already exists
		if(!file_exists($folder_url.'/'.$filename)) {
		// create full filename
		$full_url = $folder_url.'/'.$filename;
		$url = $rel_url.'/'.$filename;
		// upload the file
		$success = move_uploaded_file($file['tmp_name'], $url);
		} else {
		// create unique filename and upload file
		ini_set('date.timezone', 'Europe/London');
		$now = date('Y-m-d-His');
		$full_url = $folder_url.'/'.$now.$filename;
		$url = $rel_url.'/'.$now.$filename;
		$success = move_uploaded_file($file['tmp_name'], $url);
		}
		// if upload was successful
		if($success) {
		// save the url of the file
		$result['urls'][] = $url;
		} else {
		$result['errors'][] = "Error uploaded $filename. Please try again.";
		}
		break;
		case 3:
		// an error occured
		$result['errors'][] = "Error uploading $filename. Please try again.";
		break;
		default:
		// an error occured
		$result['errors'][] = "System error uploading $filename. Contact webmaster.";
		break;
		}
		} elseif($file['error'] == 4) {
		// no file was selected for upload
		$result['nofiles'][] = "No file Selected";
		} else {
		// unacceptable file type
		$result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
		}
//	}
	return $result;
}


}//end class archivos


?>
