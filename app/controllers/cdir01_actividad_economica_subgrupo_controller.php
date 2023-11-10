<?php
/**
* @property Cdir01ActividadEconomicaSubgrupo $Cdir01ActividadEconomicaSubgrupo
 *@property Cdir01ActividadEconomicaGrupo $Cdir01ActividadEconomicaGrupo  
 */
class Cdir01ActividadEconomicaSubgrupoController extends AppController {

    var $uses = array('Cdir01ActividadEconomicaSubgrupo','Cdir01ActividadEconomicaGrupo');
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
        $lista = $this->Cdir01ActividadEconomicaGrupo->findAll(null,null,'cod_actividad_grupo ASC');
        $this->set('lista',$lista);
        
        
    }
    
    /**
     * generamos una lista de datos que esten asociados con los que viene en 
     * @param POST (int) $this->data['parent']
     */
    public function lista(){
        $this->autoRender = false;
        $lista_ = $this->Cdir01ActividadEconomicaSubgrupo->findAll('cod_actividad_grupo = '.$this->data['parent'], null, 'cod_actividad_subgrupo ASC');
        if(!empty($lista_)){
            $lista = array();
            foreach ($lista_ as $value) {
                $value['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_subgrupo'] = $this->mascara_cuatro($value['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_subgrupo']);
                $lista[] = $value;
            }
            $cod = $this->Cdir01ActividadEconomicaSubgrupo->find('cod_actividad_grupo = '.$this->data['parent'], null,'cod_actividad_subgrupo DESC');
            echo json_encode(array('completed'=>1,
                                   'message'=>'Registros Cargados.',
                                   'new_cod'=>$this->mascara_cuatro($cod['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_subgrupo']+1),
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
        if(isset($data['Cdir01ActividadEconomicaSubgrupo']['update']) && $data['Cdir01ActividadEconomicaSubgrupo']['update']==1){
            $message = "Los Datos no fueron actualizados.";
            $completed = 0;

            $condicion = 'cod_actividad_grupo = '.$this->data['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_grupo'].' AND cod_actividad_subgrupo = '.$this->data['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_subgrupo'];
            $update = "UPDATE cdir01_actividad_economica_subgrupo SET denominacion = '".$this->data['Cdir01ActividadEconomicaSubgrupo']['denominacion']."' WHERE ".$condicion;
            $rs = $this->Cdir01ActividadEconomicaSubgrupo->query($update);
            
            if(!is_bool($rs)){//El valor que retorna el query es false en caso de error y [] en caso de execute, por lo tanto que si es bool negamos el resultado para que no ingrese
                $message = "Datos Actualizados Correctamente.";$completed = 1;
            }
            
            $cod = $this->Cdir01ActividadEconomicaSubgrupo->find('cod_actividad_grupo = '.$this->data['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_grupo'], null,'cod_actividad_subgrupo DESC');
            $lista_ = $this->Cdir01ActividadEconomicaSubgrupo->findAll('cod_actividad_grupo = '.$this->data['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_grupo'], null, 'cod_actividad_subgrupo ASC');

            $lista = array();
            foreach ($lista_ as $value) {
                $value['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_subgrupo'] = $this->mascara_cuatro($value['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_subgrupo']);
                $lista[] = $value;
            }
            
            echo json_encode(array('completed'=>$completed,
                                       'message'=>$message,
                                       'new_cod'=>$this->mascara_cuatro($cod['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_subgrupo']+1),
                                       'lista' => $lista,
                                ));
        }else{
            if($this->Cdir01ActividadEconomicaSubgrupo->save($data['Cdir01ActividadEconomicaSubgrupo'])){
                $cod = $this->Cdir01ActividadEconomicaSubgrupo->find('cod_actividad_grupo = '.$this->data['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_grupo'], null,'cod_actividad_subgrupo DESC');
                $lista_ = $this->Cdir01ActividadEconomicaSubgrupo->findAll('cod_actividad_grupo = '.$this->data['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_grupo'], null, 'cod_actividad_subgrupo ASC');

                $lista = array();
                foreach ($lista_ as $value) {
                    $value['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_subgrupo'] = $this->mascara_cuatro($value['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_subgrupo']);
                    $lista[] = $value;
                }
                echo json_encode(array('completed'=>1,
                                   'message'=>'Datos Almacenados Correctamente.',
                                   'new_cod'=>$this->mascara_cuatro($cod['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_subgrupo']+1),
                                   'lista' => $lista
                            ));
            }else{
                echo json_encode(array('completed'=>0,'message'=>'Los datos no fueron almacenados.','data'=>  $this->data));
            }
        }
        
    }
    
    
    public function eliminar() {
        $this->autoRender = false;

        $condicion = 'cod_actividad_grupo = '.$this->data['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_grupo'].' AND cod_actividad_subgrupo = '.$this->data['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_subgrupo'];
        $update = "DELETE FROM cdir01_actividad_economica_subgrupo WHERE ".$condicion;
        $rs = $this->Cdir01ActividadEconomicaSubgrupo->query($update);

        if(!is_bool($rs)){//El valor que retorna el query es false en caso de error y [] en caso de execute, por lo tanto que si es bool negamos el resultado para que no ingrese
            $cod = $this->Cdir01ActividadEconomicaSubgrupo->find('cod_actividad_grupo = '.$this->data['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_grupo'], null,'cod_actividad_subgrupo DESC');
            echo json_encode(array('completed'=>1,
                                   'message'=>'Registro Eliminado.',
                                   'new_cod'=>$this->mascara_cuatro($cod['Cdir01ActividadEconomicaSubgrupo']['cod_actividad_subgrupo']+1),
                            ));
        }else{
            echo json_encode(array('completed'=>0,'message'=>'No fue posible elminar el registro.'));
        }
        //echo json_encode($this->data);
    }
    
    
   
}
