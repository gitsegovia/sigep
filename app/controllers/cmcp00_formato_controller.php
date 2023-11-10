<?php
/*
 * Fecha: 16/03/2023
 *
 * Por Jose G. Segovia R.
 * email: jose.segovia.r@gmail.com
 *
 * sisap
 */
class Cmcp00FormatoController extends AppController
{
  public $name = 'cmcp00_formato';
  public $uses = array('cmcd00_formatos', 'cmcd01_informes','ccfd04_cierre_mes', 'cmcd00_cierre_trimestre', 'cfpd01_formulacion', 'arrd05');
  public $helpers = array('Html', 'Ajax', 'Javascript', 'Sisap');

  public function checkSession()
  {
    if (!$this->Session->check('Usuario')) {
      $this->redirect('/salir/');
      exit();
    } else {
      //$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
      //echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
      $this->requestAction('/usuarios/actualizar_user');
    }
  } //fin checksession

  public function beforeFilter()
  {
    $this->checkSession();
  }

  function index($var = null)
  {
    $this->layout = "ajax";
    $this->set('opcion', $var);
  } //fin funcion subir_archivo_transacciones


  function uploadFiles($folder, $formdata, $itemId = null)
  {
    // setup dir names absolute and relative
    $folder_url = WWW_ROOT . $folder;
    $rel_url = $folder_url;

    // create the folder if it does not exist
    
    if (!is_dir($folder_url)) {
      mkdir($folder_url, 0777, true);
    }

    // if itemId is set create an item folder
    if ($itemId) {
      // set new absolute folder
      $folder_url = WWW_ROOT . $folder . '/' . $itemId;
      // set new relative folder
      $rel_url = $folder_url . '/' . $itemId;
      // create directory
      if (!is_dir($folder_url)) {
        mkdir($folder_url);
      }
    }

    // list of permitted file types, this is only images but documents can be added
    //$permitted = array('image/gif','image/jpeg','image/pjpeg','image/png');
    $permitted = array('application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document');

    // loop through and deal with the files
    $file = $formdata;
    //	foreach($formdata as $file) {
    // replace spaces with underscores

    $filename = 'formato_memoria_cuenta.';
    if($file['type']=='application/vnd.openxmlformats-officedocument.wordprocessingml.document'){
      $filename.='docx';
    }else{
      $filename.='doc';
    }

    // assume filetype is false
    $typeOK = false;
    // check filetype is ok
    foreach ($permitted as $type) {
      // echo $file['type'];
      if ($type == $file['type']) {
        $typeOK = true;
        break;
      }
    }

    // if file type ok upload the file
    if ($typeOK) {
      // switch based on error code
      switch ($file['error']) {
        case 0:
          // check filename already exists

          // create full filename
          $url = $rel_url . '/' . $filename;
          // upload the file
          $success = move_uploaded_file($file['tmp_name'], $url);
        
          // if upload was successful
          if ($success) {
            // save the url of the file
            $result['urls'][] = $url;
            $result['name'] = $filename;
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
    } elseif ($file['error'] == 4) {
      // no file was selected for upload
      $result['errors'][] = "No file Selected";
    } else {
      // unacceptable file type
      $result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
    }
    //	}
    
    return $result;
  }

  function cargar_archivo($aleatorio)
  {
    $this->layout = "ajax";

    if (!empty($this->params['form']) && is_uploaded_file($this->params['form']['File']['tmp_name'])) {

      $fileOK = $this->uploadFiles('formato_memoria_cuenta', $this->params['form']['File']);

      if (array_key_exists('urls', $fileOK)) {

        $ver = $this->cmcd00_formatos->findCount("cod_dep=1");
        
        if($ver==0){
          $filename = $fileOK["name"];

          $sql_insert_formato = "INSERT INTO cmcd00_formatos VALUES ('1', '$filename');";
                  
          $sw1 = $this->cmcd00_formatos->execute($sql_insert_formato);
          
          if($sw1>1){
            $this->set('Message_existe','Formato almacenado con éxito');
            echo "<center><h2>Formato almacenado con éxito</h2></center>";
          }else{
            $this->set('errorMessage','No se pudo subir el formato');
            
            echo "<center><h2>1.No se pudo subir el formato</h2></center>";
          }
        }else{
          $filename = $fileOK["name"];

          $sql_insert_formato = "UPDATE cmcd00_formatos SET formato='$filename' WHERE cod_dep=1;";
                  
          $sw1 = $this->cmcd00_formatos->execute($sql_insert_formato);
          
          if($sw1>1){
            $this->set('Message_existe','Formato almacenado con éxito');
            echo "<center><h2>Formato almacenado con éxito</h2></center>";
          }else{
            $this->set('errorMessage','No se pudo subir el formato');
            
            echo "<center><h2>2.No se pudo subir el formato</h2></center>";
          }
        }
        //print_r($datos_completos);
      } //fin file ok

      if (array_key_exists('errors', $fileOK)) {            
        echo "<center><h2>3.No se pudo subir el formato</h2></center>";
      } //fin file ok

    }

  } //fin funcion cargar_archivo_transaccion

  function descargar_formato()
  {
    $this->layout = "ajax";

    $ver = $this->cmcd00_formatos->findAll("cod_dep=1");
    if(count($ver)==0){
      echo "<center><h2>No se subido ningun formato</h2></center>";
      $this->render('cargar_archivo');      
    }else{
      $folder_url = WWW_ROOT . 'formato_memoria_cuenta/'.$ver[0]["cmcd00_formatos"] ["formato"];
      $this->set('file', $folder_url);
      $this->set('filename', $ver[0]["cmcd00_formatos"] ["formato"]);
    }    
  }

  function reporte_listado($var=null){

    if($var==null){
      $this->layout="ajax";
      $trimestre= array('1'=>'Primer Trimestre','2'=>'Segundo Trimestre','3'=>'Tercer Trimestre','4'=>'Cuarto Trimestre');
        $this->concatena($trimestre, 'trimestre');
      $ano_ac = $this->ano_ejecucion();

      $this->set('ano',$ano_ac);
      $this->set('pdf','no');
      $this->set('cod_dep',$this->Session->read('SScoddep')); 
    }else{
      $ano = $this->data['reporte_personal']['ano'];
      $trimestre = $this->data['reporte_personal']['trimestre'];
      
      $condicion="ano=$ano and trimestre=$trimestre";
      //$condicion="ano=$ano";
    
      set_time_limit(0);
      ini_set("memory_limit","2560M");
      $this->layout="pdf";
      $this->set('pdf','si');
      $sql = "SELECT cod_dep, (select denominacion from arrd05 where cod_dep=informes.cod_dep) as deno_dependencia, ano, trimestre, informe, fecha_registro, ultima_modificacion from cmcd01_informes as informes WHERE ".$condicion." ORDER BY cod_dep ASC";
      $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
      $codigo_certificacion=strtoupper(substr(str_shuffle($permitted_chars), 0, 24));

      $datos = $this->cmcd00_formatos->execute($sql);

      $datos_imgs=$this->cmcd00_formatos->execute("SELECT tipo_logo_derecho, tipo_logo_izquierdo, tipo_imagen_sello, tipo_imagen_sello_firma, tipo_imagen_firma FROM cnmd06_constancia_firmante WHERE cod_dep = ".$this->Session->read('SScoddep'));
      $cod_imagen = $this->verifica_SS(1)."_".$this->verifica_SS(2)."_".$this->verifica_SS(3)."_".$this->verifica_SS(4)."_".$this->Session->read('SScoddep');


      $this->set("cod_imagen", $cod_imagen);
      $this->set('codigo_certificacion',$codigo_certificacion);
      $this->set('datos',$datos);     
        $this->set("datos_imgs", $datos_imgs);
        $this->set("cod_dep_set", $this->Session->read('SScoddep'));
      $this->set('ano',$ano);
      $this->set('trimestre',$trimestre);
    }
  }

}//fin de la clase
