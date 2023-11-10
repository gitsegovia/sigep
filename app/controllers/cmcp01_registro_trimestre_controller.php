<?php
/*
 * Fecha: 16/03/2023
 *
 * Por Jose G. Segovia R.
 * email: jose.segovia.r@gmail.com
 *
 * sisap
 */
class Cmcp01RegistroTrimestreController extends AppController
{
  public $name = 'cmcp01_registro_trimestre';
  public $uses = array('cmcd00_formatos', 'cmcd01_informes', 'ccfd04_cierre_mes', 'cmcd00_cierre_trimestre', 'cfpd01_formulacion', 'arrd05');
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

  public function verifica_SS($i)
  {
      /**
       * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
       * para ser insertados en todas las tablas.
       * */
      switch ($i) {
          case 1:return $this->Session->read('SScodpresi');
              break;
          case 2:return $this->Session->read('SScodentidad');
              break;
          case 3:return $this->Session->read('SScodtipoinst');
              break;
          case 4:return $this->Session->read('SScodinst');
              break;
          case 5:return $this->Session->read('SScoddep');
              break;
          case 6:return $this->Session->read('entidad_federal');
              break;
          default:
              return "NULO";
      }//fin switch
  }//fin verifica_SS

  public function SQLCA($ano=null)//sql para busqueda de codigos de arranque con y sin año
  {
      $sql_re='';
      
      if ($ano!=null) {
          $sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
          $sql_re .= "ano=".$ano."  ";
      } else {
          $sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
      }
      return $sql_re;
  }//fin funcion SQLCA

  function index($var = null)
  {
    $this->layout = "ajax";
    $ano = $this->ano_ejecucion();
    
    $datos = $this->cmcd01_informes->findAll($this->SQLCA($ano));
    $cierre = $this->cmcd00_cierre_trimestre->findAll('cod_dep='.$this->verifica_SS(5).' and ano='.$ano, null, 'ano ASC', null);

    $trimestre=1;
    if(date("m")>9){
      $trimestre=4;
    }else if(date("m")>6){
      $trimestre=3;
    }else if(date("m")>3){
      $trimestre=2;
    }

    $this->set('datos', $datos); 
    if(count($cierre)>0){
      $this->set('trimestre', $cierre[0]['cmcd00_cierre_trimestre']['trimestre']);
      $this->set('cierre', $cierre[0]['cmcd00_cierre_trimestre']['estatus']);
    }else{
      $this->set('trimestre', $trimestre);
      $this->set('cierre', '0');
    }
    
  } //fin funcion subir_archivo_transacciones

  function consultar()
  {
    $this->layout = "ajax";
    $ano = $this->ano_ejecucion();

    $lista = $this->arrd05->generateList('where cod_presi = 1  and cod_entidad =12 and cod_tipo_inst =30 and cod_inst = 12', ' cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
    if($lista !=null){
        $this->concatena($lista, 'listadependencia');
    }else{
        $this->set('listadependencia','');
    }
    $this->set('cod_dep',$this->Session->read('SScoddep'));
    
    $datos = $this->cmcd01_informes->findAll($this->SQLCA($ano));
    $cierre = $this->cmcd00_cierre_trimestre->findAll('cod_dep='.$this->verifica_SS(5).' and ano='.$ano, null, 'ano ASC', null);
    
    $this->set('ano', $ano);
    $this->set('datos', $datos);
    if(count($cierre)>0){
      $this->set('trimestre', $cierre[0]['cmcd00_cierre_trimestre']['trimestre']);
      $this->set('cierre', $cierre[0]['cmcd00_cierre_trimestre']['estatus']);
    }else{
      $this->set('trimestre', $trimestre);
      $this->set('cierre', '0');
    }
    
  } //fin funcion subir_archivo_transacciones

  function consultar_dependencia($cod_dep)
  {
    $this->layout = "ajax";
    $ano = $this->ano_ejecucion();

    $datos = $this->cmcd01_informes->findAll("cod_dep=".$cod_dep." and ano=".$ano);
    $cierre = $this->cmcd00_cierre_trimestre->findAll('cod_dep='.$cod_dep.' and ano='.$ano, null, 'ano ASC', null);
    
    $this->set('ano', $ano);
    $this->set('cod_dep', $cod_dep);
    $this->set('datos', $datos);
    if(count($cierre)>0){
      $this->set('trimestre', $cierre[0]['cmcd00_cierre_trimestre']['trimestre']);
      $this->set('cierre', $cierre[0]['cmcd00_cierre_trimestre']['estatus']);
    }else{
      $this->set('trimestre', $trimestre);
      $this->set('cierre', '0');
    }
    
  } 

  function borrar_informe($cod_dep,$ano, $trimestre)
  {
    $this->layout = "ajax";

    $sql_delete = "DELETE FROM cmcd01_informes WHERE cod_dep=".$cod_dep." AND ano=".$ano." AND trimestre=".$trimestre;
    
    $delete_data = $this->cmcd01_informes->execute($sql_delete);
    if($delete_data>0){
        $this->set('Message_existe', 'Informe eliminado con éxito');
    } else {
        $this->set('errorMessage', '1.No se pudo eliminar el informe');
    }

    $datos = $this->cmcd01_informes->execute("cod_dep=".$cod_dep." and ano=".$ano);
    $cierre = $this->cmcd00_cierre_trimestre->findAll('cod_dep='.$cod_dep.' and ano='.$ano, null, 'ano ASC', null);
    
    $this->set('ano', $ano);
    $this->set('cod_dep', $cod_dep);
    $this->set('datos', $datos);
    if(count($cierre)>0){
      $this->set('trimestre', $cierre[0]['cmcd00_cierre_trimestre']['trimestre']);
      $this->set('cierre', $cierre[0]['cmcd00_cierre_trimestre']['estatus']);
    }else{
      $this->set('trimestre', $trimestre);
      $this->set('cierre', '0');
    }
    $this->render('consultar_dependencia');
    
  } 

  function uploadFiles($folder, $formdata, $name, $itemId = null)
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
      $rel_url = $folder_url;
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

    $filename = $name;
    if($file['type']=='application/vnd.openxmlformats-officedocument.wordprocessingml.document'){
      $filename.='.docx';
    }else{
      $filename.='.doc';
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

  function cargar_archivo($trimestre, $aleatorio)
  {
    $this->layout = "ajax";
		$ano_ac = $this->ano_ejecucion();
    $cod_dep = $this->verifica_SS(5);

    
    if (!empty($this->params['form']) && is_uploaded_file($this->params['form']['File']['tmp_name'])) {
        $fileOK = $this->uploadFiles('formato_memoria_cuenta', $this->params['form']['File'], 'dep_'.$cod_dep.'_trimestre'.'_'.$trimestre.'_'.$ano_ac, $cod_dep);

        if (array_key_exists('urls', $fileOK)) {
            $ver = $this->cmcd01_informes->findCount($this->SQLCA($ano_ac)." and trimestre=".$trimestre);

            $fecha_registro		= date("d/m/Y");
            if ($ver==0) {
                $filename = $fileOK["name"];

                $sql_insert_formato = "INSERT INTO cmcd01_informes VALUES ($cod_dep, $ano_ac, $trimestre, '$filename', '$fecha_registro', '$fecha_registro');";

                $sw1 = $this->cmcd00_formatos->execute($sql_insert_formato);

                if ($sw1>1) {
                    $this->set('Message_existe', 'Informe almacenado con éxito');
                } else {
                    $this->set('errorMessage', '1.No se pudo subir el informe');
                }
            } else {
                $filename = $fileOK["name"];

                $sql_insert_formato = "UPDATE cmcd01_informes SET informe='$filename', ultima_modificacion='$fecha_registro' WHERE cod_dep=$cod_dep and ano=$ano_ac and trimestre=$trimestre;";

                $sw1 = $this->cmcd00_formatos->execute($sql_insert_formato);

                if ($sw1>1) {
                    $this->set('Message_existe', 'Informe almacenado con éxito');
                } else {
                    $this->set('errorMessage', '2. No se pudo subir el informe');
                }
            }
        //print_r($datos_completos);
        } //fin file ok

        if (array_key_exists('errors', $fileOK)) {
            $this->set('errorMessage', '3. No se pudo subir el informe');
        } //fin file ok
    } else {
        $this->set('errorMessage', '4. No se pudo subir el informe');
    }

  } //fin funcion cargar_archivo_transaccion

  function descargar_formato($cod_dep, $informe)
  {
    $this->layout = "ajax";

    $folder_url = WWW_ROOT . 'formato_memoria_cuenta/'.$cod_dep.'/'.$informe;
    $this->set('file', $folder_url);
    $this->set('filename', $informe);
  }

}//fin de la clase
