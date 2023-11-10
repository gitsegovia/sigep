<?php
/**
* @property Cdir01LegislacionSeccion $Cdir01LegislacionSeccion
 */
class Cdir01LegislacionSeccionController extends AppController {

    var $uses = array('Cdir01LegislacionSeccion');
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
        //Leemos la tabla para extraer el cod_actividad_grupo mas alto y sumar uno
        /*$cod = $this->Cdir01LegislacionSeccion->find(null,null,'cod_actividad_grupo DESC');//Trae un solo valor
        $this->data['Cdir01LegislacionSeccion']['cod_actividad_grupo']= $this->mascara_cuatro($cod['Cdir01ActividadEconomicaGrupo']['cod_actividad_grupo']+1);
        $lista = $this->Cdir01ActividadEconomicaGrupo->findAll(null,null,'cod_actividad_grupo ASC');
        $this->set('lista',$lista);*/
        
    }
    
    
    
    /**
     * generamos una lista de datos que esten asociados con los que viene en 
     * @param POST (int) $this->data['parent']
     */
    public function lista(){
        $this->autoRender = false;
        $lista_ = $this->Cdir01LegislacionSeccion->findAll('cod_personalidad = '.$this->data['parent'], null, 'cod_seccion ASC');
        if(!empty($lista_)){
            $lista = array();
            foreach ($lista_ as $value) {
                $value['Cdir01LegislacionSeccion']['cod_seccion'] = $this->mascara_cuatro($value['Cdir01LegislacionSeccion']['cod_seccion']);
                $lista[] = $value;
            }
            $cod = $this->Cdir01LegislacionSeccion->find('cod_personalidad = '.$this->data['parent'], null,'cod_seccion DESC');
            echo json_encode(array('completed'=>1,
                                   'message'=>'Registros Cargados.',
                                   'new_cod'=>$this->mascara_cuatro($cod['Cdir01LegislacionSeccion']['cod_seccion']+1),
                                   'lista' => $lista
                            ));
        }else{
            echo json_encode(array('completed'=>0,
                                   'message'=>'No existen Registros Creados.',
                                   'new_cod'=>$this->mascara_cuatro(1),
                            ));
        }
        
    }
    
    
   public function guardar() {
        $this->autoRender = false;
        //$this->Cdir01ActividadEconomicaGrupo->primaryKey = 'cod_actividad_grupo';
        $data = $this->data;
        //Verificamos primero que sea un update ya que si es asi viene por el event del editar
        if(isset($data['Cdir01LegislacionSeccion']['update']) && $data['Cdir01LegislacionSeccion']['update']==1){
            $message = "Los Datos no fueron actualizados.";
            $completed = 0;

            $condicion = 'cod_personalidad = '.$this->data['Cdir01LegislacionSeccion']['cod_personalidad'].' AND cod_seccion = '.$this->data['Cdir01LegislacionSeccion']['cod_seccion'];
            $update = "UPDATE cdir01_legislacion_seccion SET denominacion = '".$this->data['Cdir01LegislacionSeccion']['denominacion']."' WHERE ".$condicion;
            $rs = $this->Cdir01LegislacionSeccion->query($update);
            
            if(!is_bool($rs)){//El valor que retorna el query es false en caso de error y [] en caso de execute, por lo tanto que si es bool negamos el resultado para que no ingrese
                $message = "Datos Actualizados Correctamente.";$completed = 1;
            }
            
            $cod = $this->Cdir01LegislacionSeccion->find('cod_personalidad = '.$this->data['Cdir01LegislacionSeccion']['cod_personalidad'], null,'cod_seccion DESC');
            $lista_ = $this->Cdir01LegislacionSeccion->findAll('cod_personalidad = '.$this->data['Cdir01LegislacionSeccion']['cod_personalidad'], null, 'cod_seccion ASC');

            $lista = array();
            foreach ($lista_ as $value) {
                $value['Cdir01LegislacionSeccion']['cod_seccion'] = $this->mascara_cuatro($value['Cdir01LegislacionSeccion']['cod_seccion']);
                $lista[] = $value;
            }
            
            echo json_encode(array('completed'=>$completed,
                                       'message'=>$message,
                                       'new_cod'=>$this->mascara_cuatro($cod['Cdir01LegislacionSeccion']['cod_seccion']+1),
                                       'lista' => $lista,
                                ));
        }else{
            if($this->Cdir01LegislacionSeccion->save($data['Cdir01LegislacionSeccion'])){
                $cod = $this->Cdir01LegislacionSeccion->find('cod_personalidad = '.$this->data['Cdir01LegislacionSeccion']['cod_personalidad'], null,'cod_seccion DESC');
                $lista_ = $this->Cdir01LegislacionSeccion->findAll('cod_personalidad = '.$this->data['Cdir01LegislacionSeccion']['cod_personalidad'], null, 'cod_seccion ASC');

                $lista = array();
                foreach ($lista_ as $value) {
                    $value['Cdir01LegislacionSeccion']['cod_seccion'] = $this->mascara_cuatro($value['Cdir01LegislacionSeccion']['cod_seccion']);
                    $lista[] = $value;
                }
                echo json_encode(array('completed'=>1,
                                   'message'=>'Datos Almacenados Correctamente.',
                                   'new_cod'=>$this->mascara_cuatro($cod['Cdir01LegislacionSeccion']['cod_seccion']+1),
                                   'lista' => $lista
                            ));
            }else{
                echo json_encode(array('completed'=>0,'message'=>'Los datos no fueron almacenados.','data'=>  $this->data));
            }
        }
        
    }
    
    
    public function eliminar() {
        $this->autoRender = false;

        $condicion = 'cod_personalidad = '.$this->data['Cdir01LegislacionSeccion']['cod_personalidad'].' AND cod_seccion = '.$this->data['Cdir01LegislacionSeccion']['cod_seccion'];
        $update = "DELETE FROM cdir01_legislacion_seccion WHERE ".$condicion;
        $rs = $this->Cdir01LegislacionSeccion->query($update);

        if(!is_bool($rs)){//El valor que retorna el query es false en caso de error y [] en caso de execute, por lo tanto que si es bool negamos el resultado para que no ingrese
            $cod = $this->Cdir01LegislacionSeccion->find('cod_personalidad = '.$this->data['Cdir01LegislacionSeccion']['cod_personalidad'], null,'cod_seccion DESC');
            echo json_encode(array('completed'=>1,
                                   'message'=>'Registro Eliminado.',
                                   'new_cod'=>$this->mascara_cuatro($cod['Cdir01LegislacionSeccion']['cod_seccion']+1)
                            ));
        }else{
            echo json_encode(array('completed'=>0,'message'=>'No fue posible elminar el registro.'));
        }
        //echo json_encode($this->data);
    }
}
