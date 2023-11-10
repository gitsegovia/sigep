<?php
/**
* @property Cdir01LegislacionSeccionDetalle $Cdir01LegislacionSeccionDetalle
* @property Cdir01UnidadTributaria $Cdir01UnidadTributaria 
* @property Cdir01LegislacionSeccion $Cdir01LegislacionSeccion 
*/
class Cdir01LegislacionSeccionDetalleController extends AppController {

    var $uses = array('Cdir01LegislacionSeccionDetalle','Cdir01LegislacionSeccion','Cdir01UnidadTributaria');
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
        //Mando Unidad Tributaria al Formulario
        $this->Cdir01UnidadTributaria->primaryKey = 'codigo';
        $this->Cdir01UnidadTributaria->id = '1';
        $_data = $this->Cdir01UnidadTributaria->read();
        $this->data['Cdir01LegislacionSeccionDetalle']['unidad_tributaria']= $this->Formato2($_data['cdir01_unidad_tributaria']['valor']);
        
        
    }
    
    /**
     * generamos una lista de datos que esten asociados con los que viene en 
     * @param POST (int) $this->data['parent']
     */
    public function lista(){
        $this->autoRender = false;
        $lista_ = $this->Cdir01LegislacionSeccionDetalle->findAll('cod_personalidad = '.$this->data['cod_personalidad'].' AND cod_seccion = '.$this->data['cod_seccion'], null, 'cod_detalle ASC');
        if(!empty($lista_)){
            $lista = array();
            foreach ($lista_ as $value) {
                $value['Cdir01LegislacionSeccionDetalle']['cod_detalle'] = $this->mascara_cuatro($value['Cdir01LegislacionSeccionDetalle']['cod_detalle']);
                $value['Cdir01LegislacionSeccionDetalle']['cantidad_ut'] = $this->Formato2($value['Cdir01LegislacionSeccionDetalle']['cantidad_ut']);
                $value['Cdir01LegislacionSeccionDetalle']['valor_bs'] = $this->Formato2($value['Cdir01LegislacionSeccionDetalle']['valor_bs']);
                $lista[] = $value;
            }
            $cod = $this->Cdir01LegislacionSeccionDetalle->find('cod_personalidad = '.$this->data['cod_personalidad'].' AND cod_seccion = '.$this->data['cod_seccion'], null,'cod_detalle DESC');
            echo json_encode(array('completed'=>1,
                                   'message'=>'Registros Cargados.',
                                   'new_cod'=>$this->mascara_cuatro($cod['Cdir01LegislacionSeccionDetalle']['cod_detalle']+1),
                                   'lista' => $lista
                            ));
        }else{
            echo json_encode(array('completed'=>0,
                                   'message'=>'No existen Registros Creados.',
                                   'new_cod'=>$this->mascara_cuatro(1),
                            ));
        }
        
    }
    
    /**
     * Se usa para llenar el select que esta en la vista index y retorna Json de secciones
     * recibe un POST con el cod_personalidad
     */
    public function secciones(){
        $this->autoRender = false;
        $lista_ = $this->Cdir01LegislacionSeccion->findAll('cod_personalidad = '.$this->data['Cdir01LegislacionSeccion']['cod_personalidad'], null, 'cod_seccion ASC');
        if(!empty($lista_)){
            $lista = array();
            foreach ($lista_ as $value) {
                $value['Cdir01LegislacionSeccion']['cod_seccion'] = $this->mascara_cuatro($value['Cdir01LegislacionSeccion']['cod_seccion']);
                $lista[] = $value;
            }
            echo json_encode(array('completed'=>1,
                                   'message'=>'Registros Cargados.',
                                   'data' => $lista
                            ));
        }else{
            echo json_encode(array('completed'=>0,
                                   'message'=>'No existen Registros Creados.',
                            ));
        }
        
    }
    
    
   public function guardar() {
        $this->autoRender = false;
        //$this->Cdir01ActividadEconomicaGrupo->primaryKey = 'cod_actividad_grupo';
        $this->Cdir01UnidadTributaria->primaryKey = 'codigo';
        $this->Cdir01UnidadTributaria->id = '1';
        $_data = $this->Cdir01UnidadTributaria->read();
        
        
        $data = $this->data;
        $data['Cdir01LegislacionSeccionDetalle']['cantidad_ut'] = str_replace('.', '', $data['Cdir01LegislacionSeccionDetalle']['cantidad_ut']);
        $cantidad_ut = $data['Cdir01LegislacionSeccionDetalle']['cantidad_ut'] = str_replace(',', '.', $data['Cdir01LegislacionSeccionDetalle']['cantidad_ut']);
        $valor_bs = $data['Cdir01LegislacionSeccionDetalle']['valor_bs'] = $_data['cdir01_unidad_tributaria']['valor'] * $data['Cdir01LegislacionSeccionDetalle']['cantidad_ut'];
        
        //Verificamos primero que sea un update ya que si es asi viene por el event del editar
        if(isset($data['Cdir01LegislacionSeccionDetalle']['update']) && $data['Cdir01LegislacionSeccionDetalle']['update']==1){
            $message = "Los Datos no fueron actualizados.";
            $completed = 0;

            $condicion = 'cod_personalidad = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_personalidad'].' AND cod_seccion = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_seccion'].' AND cod_detalle = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_detalle'];
            $update = "UPDATE cdir01_legislacion_seccion_detalle SET denominacion = '".$data['Cdir01LegislacionSeccionDetalle']['denominacion']."', cantidad_ut = '$cantidad_ut', valor_bs = '$valor_bs'  WHERE ".$condicion;
            $rs = $this->Cdir01LegislacionSeccionDetalle->query($update);
            
            if(!is_bool($rs)){//El valor que retorna el query es false en caso de error y [] en caso de execute, por lo tanto que si es bool negamos el resultado para que no ingrese
                $message = "Datos Actualizados Correctamente.";$completed = 1;
            }
            
            $cod = $this->Cdir01LegislacionSeccionDetalle->find('cod_personalidad = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_personalidad'].' AND cod_seccion = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_seccion'], null,'cod_detalle DESC');
            /*$lista_ = $this->Cdir01LegislacionSeccionDetalle->findAll('cod_personalidad = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_personalidad'].' AND cod_seccion = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_seccion'], null, 'cod_detalle ASC');

            $lista = array();
            foreach ($lista_ as $value) {
                $value['Cdir01LegislacionSeccionDetalle']['cod_detalle'] = $this->mascara_cuatro($value['Cdir01LegislacionSeccionDetalle']['cod_detalle']);
                $lista[] = $value;
            }*/
            
            echo json_encode(array('completed'=>$completed,
                                       'message'=>$message,
                                       'new_cod'=>$this->mascara_cuatro($cod['Cdir01LegislacionSeccion']['cod_seccion']+1),
                                       //'lista' => $lista,
                                       'q'=>$update
                                ));
        }else{
            
            if($this->Cdir01LegislacionSeccionDetalle->save($data['Cdir01LegislacionSeccionDetalle'])){
                $cod = $this->Cdir01LegislacionSeccionDetalle->find('cod_personalidad = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_personalidad'].' AND cod_seccion = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_seccion'], null,'cod_detalle DESC');
                $lista_ = $this->Cdir01LegislacionSeccionDetalle->findAll('cod_personalidad = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_personalidad'].' AND cod_seccion = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_seccion'], null, 'cod_detalle ASC');

                $lista = array();
                foreach ($lista_ as $value) {
                    $value['Cdir01LegislacionSeccionDetalle']['cod_detalle'] = $this->mascara_cuatro($value['Cdir01LegislacionSeccionDetalle']['cod_detalle']);
                    $lista[] = $value;
                }
                echo json_encode(array('completed'=>1,
                                   'message'=>'Datos Almacenados Correctamente.',
                                   'new_cod'=>$this->mascara_cuatro($cod['Cdir01LegislacionSeccionDetalle']['cod_detalle']+1),
                                   'lista' => $lista
                            ));
            }else{
                echo json_encode(array('completed'=>0,'message'=>'Los datos no fueron almacenados.','data'=>  $data));
            }
        }
        
    }
    
    
    public function eliminar() {
        $this->autoRender = false;

        $condicion = 'cod_personalidad = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_personalidad'].' AND cod_seccion = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_seccion'].' AND cod_detalle = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_detalle'];
        $update = "DELETE FROM cdir01_legislacion_seccion_detalle WHERE ".$condicion;
        $rs = $this->Cdir01LegislacionSeccionDetalle->query($update);

        if(!is_bool($rs)){//El valor que retorna el query es false en caso de error y [] en caso de execute, por lo tanto que si es bool negamos el resultado para que no ingrese
            $cod = $this->Cdir01LegislacionSeccionDetalle->find('cod_personalidad = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_personalidad'].' AND cod_seccion = '.$this->data['Cdir01LegislacionSeccionDetalle']['cod_seccion'], null,'cod_detalle DESC');
            echo json_encode(array('completed'=>1,
                                   'message'=>'Registro Eliminado.',
                                   'new_cod'=>$this->mascara_cuatro($cod['Cdir01LegislacionSeccionDetalle']['cod_seccion']+1)
                            ));
        }else{
            echo json_encode(array('completed'=>0,'message'=>'No fue posible elminar el registro.'));
        }
        //echo json_encode($this->data);
    }
    
    
   
}
