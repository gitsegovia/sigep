<?php
/**
 * @property Cdird02Institucion $Cdird02Institucion 
 */
class Cdird02InstitucionController extends AppController {

    var $uses = array('Cdird02Institucion');
    var $helpers = array('Html','Ajax','Javascript', 'Sisap');
    

        
    public function checkSession() {
        if (!$this->Session->check('Usuario')) {
            $this->redirect('/salir/');
            exit();
        } else {
            $this->requestAction('/usuarios/actualizar_user');
        }
    }


    public function beforeFilter() {
        $this->checkSession();
    }


    public function index() {
        $this->layout = "ajax";
        $cod_institucion = '01';//$this->Session->read('Usuario.cod_inst');
        //aqui mandamos la data al formulario
        $datos = $this->Cdird02Institucion->find('cod_institucion='.$cod_institucion);
        $this->data = array(
            'Cdird02Institucion'=> $datos['cdird02_institucion']
        );
        
        
        
    }
    
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
                            $SQL_ = "UPDATE cdird02_institucion SET logo_derecho='{$image_1000}' WHERE cod_institucion='1'; ";  
                            break;
                        case '2': //Logo Izquierdo
                            $SQL_ = "UPDATE cdird02_institucion SET logo_izquierdo='{$image_1000}' WHERE cod_institucion='1'; ";  
                            break;
                        case '3': //Imagen Sello
                            $SQL_ = "UPDATE cdird02_institucion SET imagen_sello='{$image_1000}' WHERE cod_institucion='1'; ";  
                            break;
                        case '4': //Imagen Sello Firma
                            $SQL_ = "UPDATE cdird02_institucion SET imagen_sello_firma='{$image_1000}' WHERE cod_institucion='1'; ";  
                            break;
                        case '5': //Imagen Firma
                            $SQL_ = "UPDATE cdird02_institucion SET imagen_firma='{$image_1000}' WHERE cod_institucion='1'; ";  
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
        
        $SQL_ = "UPDATE cdird02_institucion SET nombre_institucion='".$data['nombre_institucion']."', secretaria_direccion='".$data['secretaria_direccion']."', superintendencia='".$data['superintendencia']."', funcionario_firmante='".$data['funcionario_firmante']."', cargo_firmante = '".$data['cargo_firmante']."'    WHERE cod_institucion='1'; ";  
        
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
                    $SQL_ = "SELECT logo_derecho AS imagen FROM cdird02_institucion WHERE cod_institucion='1'; ";  
                    break;
                case '2': //Logo Izquierdo
                    $SQL_ = "SELECT logo_izquierdo AS imagen FROM cdird02_institucion WHERE cod_institucion='1'; ";  
                    break;
                case '3': //Imagen Sello
                    $SQL_ = "SELECT imagen_sello AS imagen FROM cdird02_institucion WHERE cod_institucion='1'; ";  
                    break;
                case '4': //Imagen Sello Firma
                    $SQL_ = "SELECT imagen_sello_firma AS imagen FROM cdird02_institucion WHERE cod_institucion='1'; ";  
                    break;
                case '5': //Imagen Firma
                    $SQL_ = "SELECT imagen_firma AS imagen FROM cdird02_institucion WHERE cod_institucion='1'; ";  
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
}
