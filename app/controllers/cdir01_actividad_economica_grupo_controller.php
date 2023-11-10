<?php
/**
* @property Cdir01ActividadEconomicaGrupo $Cdir01ActividadEconomicaGrupo
 */
class Cdir01ActividadEconomicaGrupoController extends AppController {

    var $uses = array('Cdir01ActividadEconomicaGrupo');
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
        $cod = $this->Cdir01ActividadEconomicaGrupo->find(null,null,'cod_actividad_grupo DESC');//Trae un solo valor
        $this->data['Cdir01ActividadEconomicaGrupo']['cod_actividad_grupo']= $this->mascara_cuatro($cod['Cdir01ActividadEconomicaGrupo']['cod_actividad_grupo']+1);
        $lista = $this->Cdir01ActividadEconomicaGrupo->findAll(null,null,'cod_actividad_grupo ASC');
        $this->set('lista',$lista);
    }
    
    public function guardar() {
        $this->autoRender = false;
        //$this->Cdir01ActividadEconomicaGrupo->primaryKey = 'cod_actividad_grupo';
        $data = $this->data;
        //Verificamos primero que sea un update ya que si es asi viene por el event del editar
        if(isset($data['Cdir01ActividadEconomicaGrupo']['update']) && $data['Cdir01ActividadEconomicaGrupo']['update']==1){
            $message = "Los Datos no fueron actualizados.";
            $completed = 0;
            $this->Cdir01ActividadEconomicaGrupo->primaryKey = 'cod_actividad_grupo';
            $this->Cdir01ActividadEconomicaGrupo->id = (int) $this->data['Cdir01ActividadEconomicaGrupo']['cod_actividad_grupo'];
            if($this->Cdir01ActividadEconomicaGrupo->saveField('denominacion', $this->data['Cdir01ActividadEconomicaGrupo']['denominacion'])){
                $message = "Datos Actualizados Correctamente.";$completed = 1;
            }
            
            $cod = $this->Cdir01ActividadEconomicaGrupo->find(null,null,'cod_actividad_grupo DESC');
            $lista_ = $this->Cdir01ActividadEconomicaGrupo->findAll(null,null,'cod_actividad_grupo ASC');
            $lista = array();//Esta se envia con los resultados actuales de la tabla en orden ASC
            foreach ($lista_ as $value) {
                $value['Cdir01ActividadEconomicaGrupo']['cod_actividad_grupo'] = $this->mascara_cuatro($value['Cdir01ActividadEconomicaGrupo']['cod_actividad_grupo']);
                $lista[] = $value;
            }
            
            echo json_encode(array('completed'=>$completed,
                                       'message'=>$message,
                                       'new_cod'=>$this->mascara_cuatro($cod['Cdir01ActividadEconomicaGrupo']['cod_actividad_grupo']+1),
                                       'lista' => $lista,
                                       'data' => $this->data
                                ));
        }else{
            if($this->Cdir01ActividadEconomicaGrupo->save($data['Cdir01ActividadEconomicaGrupo'])){
                $cod = $this->Cdir01ActividadEconomicaGrupo->find(null,null,'cod_actividad_grupo DESC');
                $lista_ = $this->Cdir01ActividadEconomicaGrupo->findAll(null,null,'cod_actividad_grupo ASC');

                $lista = array();
                foreach ($lista_ as $value) {
                    $value['Cdir01ActividadEconomicaGrupo']['cod_actividad_grupo'] = $this->mascara_cuatro($value['Cdir01ActividadEconomicaGrupo']['cod_actividad_grupo']);
                    $lista[] = $value;
                }
                echo json_encode(array('completed'=>1,
                                       'message'=>'Datos Almacenados Correctamente.',
                                       'new_cod'=>$this->mascara_cuatro($cod['Cdir01ActividadEconomicaGrupo']['cod_actividad_grupo']+1),
                                       'lista' => $lista
                                ));
            }else{
                echo json_encode(array('completed'=>0,'message'=>'Los datos no fueron almacenados.'));
            }
        }
        
    }
    
    
    public function eliminar() {
        $this->autoRender = false;
        $this->Cdir01ActividadEconomicaGrupo->primaryKey = 'cod_actividad_grupo';
        $this->Cdir01ActividadEconomicaGrupo->id = $this->data['Cdir01ActividadEconomicaGrupo']['cod_actividad_grupo'];
        if($this->Cdir01ActividadEconomicaGrupo->delete()){
            $cod = $this->Cdir01ActividadEconomicaGrupo->find(null,null,'cod_actividad_grupo DESC');
            echo json_encode(array('completed'=>1,
                                   'message'=>'Registro Eliminado.',
                                   'new_cod'=>$this->mascara_cuatro($cod['Cdir01ActividadEconomicaGrupo']['cod_actividad_grupo']+1),
                            ));
        }else{
            echo json_encode(array('completed'=>0,'message'=>'No fue posible elminar el registro.'));
        }
        //echo json_encode($this->data);
    }
    
   
}
