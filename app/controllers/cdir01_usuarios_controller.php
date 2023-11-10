<?php
/**
* @property Cdir01Usuarios $Cdir01Usuarios
 */
class Cdir01UsuariosController extends AppController {

    var $uses = array('Cdir01Usuarios');
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
        $data = $this->data;
        if(isset($data['Cdir01Usuarios']['id_usuario'])){
            $this->Cdir01Usuarios->primaryKey = 'id_usuario';
            $this->Cdir01Usuarios->id = $data['Cdir01Usuarios']['id_usuario'];
            $user_data = $this->Cdir01Usuarios->read();
            $this->data['Cdir01Usuarios'] = $user_data['cdir01_usuarios'];
            
            $this->set('activar_form',FALSE);
        }else{
            $this->set('activar_form',TRUE);
        }
    }
    
    
    /**
     * Este metodo se usuara para guardar y modificar datos ya alamacenados,
     * si a $guardar se le envia 1, es porque activar_form se paso en TRUE, por lo tanto
     * sabemos que no estamos editanto ninguna informacion sino que es un registro que se creara nuevo,
     * ahora si no se le pasa ningun valor en este caso tomara 0 y sabemos que activar_form fuel FALSE,
     * por lo tanto debemo modificar el registro actual, debemos recordar que el campo id_usuario o login en el 
     * Form no se puede editar.
     */
    public function guardar($guardar = 0){
        $this->autoRender = false;
        //Recibo los datos y los alamaceno
        $data = $this->data['Cdir01Usuarios'];
        //Primero debemos consultar el id_usuario, si existe retornamos un msj diciendo que no se puede registrar
        
        if($guardar){
            $rs = $this->Cdir01Usuarios->find("id_usuario = '".$data['id_usuario']."'");
            if(!$rs){//false lo creamos
                //echo json_encode($rs);
                if($this->Cdir01Usuarios->save($data)){
                    echo json_encode(array('completed'=>1,'message'=>'Datos Almacenados Correctamente.'));
                }else{
                    echo json_encode(array('completed'=>0,'message'=>'Los datos no fueron almacenados.','login'=>0));
                }
            }else{//aqui mandamos un msj
                echo json_encode(array('completed'=>0,'message'=>'El login suministrado ya se encuentra en uso.','login'=>1));
            } 
        }else{
            $this->Cdir01Usuarios->primaryKey = 'id_usuario';
            $this->Cdir01Usuarios->id = $data['Cdir01Usuarios']['id_usuario'];
            unset($data['Cdir01Usuarios']['id_usuario']);
            if($this->Cdir01Usuarios->save($data)){
                echo json_encode(array('completed'=>1,'message'=>'Datos Actualizados Correctamente.','update'=>1));
            }else{
                echo json_encode(array('completed'=>0,'message'=>'Los datos no fueron almacenados.','login'=>0));
            } 
        }
    }
    
    
    public function lista() {
        $this->layout = 'ajax';
        
        $usuarios = $this->Cdir01Usuarios->findAll(null,null,'ORDER BY id_usuario ASC');
        
        $this->set('usuarios',$usuarios);
    }
    
    public function eliminar($id_usuario = null) {
        //$this->layout = 'ajax';
        if($id_usuario!=null){
            $this->Cdir01Usuarios->primaryKey = 'id_usuario';
            $this->Cdir01Usuarios->id = $id_usuario;
            
            if($this->Cdir01Usuarios->delete())
                $this->set('Message_existe','Usuario Eliminado Satisfactoriamente.');
            else
                $this->set('errorMessage','No fue posible eliminar el usuario.');
            
        }
        $usuarios = $this->Cdir01Usuarios->findAll(null,null,'ORDER BY id_usuario ASC');
        $this->set('usuarios',$usuarios);
        
        
        $this->render('lista', 'ajax');
        
    }
}
