<?php
/**
* @property Cdir01UnidadTributaria $Cdir01UnidadTributaria
 */
class Cdir01UnidadTributariaController extends AppController {

    var $uses = array('Cdir01UnidadTributaria');
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
        $this->Cdir01UnidadTributaria->primaryKey = 'codigo';
        $this->Cdir01UnidadTributaria->id = '1';
        $_data = $this->Cdir01UnidadTributaria->read();
        
        $_data['cdir01_unidad_tributaria']['valor'] = $this->Formato2($_data['cdir01_unidad_tributaria']['valor']);
        $this->data['Cdir01UnidadTributaria'] = $_data['cdir01_unidad_tributaria'];
        
    }
    
    public function guardar() {
        $this->autoRender = false;
        $this->Cdir01UnidadTributaria->primaryKey = 'codigo';
        $this->Cdir01UnidadTributaria->id = '1';
        $data = $this->data;
        $data['Cdir01UnidadTributaria']['valor'] = str_replace('.', '',$data['Cdir01UnidadTributaria']['valor']);
        $data['Cdir01UnidadTributaria']['valor'] = str_replace(',', '.',$data['Cdir01UnidadTributaria']['valor']);
        if($this->Cdir01UnidadTributaria->save($data['Cdir01UnidadTributaria'])){
            echo json_encode(array('completed'=>1,'message'=>'Datos Almacenados Correctamente.'));
        }else{
            echo json_encode(array('completed'=>0,'message'=>'Los datos no fueron almacenados.','data'=>$data['Cdir01UnidadTributaria']['valor']));
        }
        
    }
    
    
   
}
