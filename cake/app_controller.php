<?php
/**
 * This is a placeholder class.
 * Create the same file in app/app_controller.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		cake
 * @subpackage	cake.cake
 */

/**
 * @property MonitorActividad $MonitorActividad 
 * @property RequestHandler $RequestHandler 
 */


class AppController extends Controller {
    
    var $components = array('RequestHandler');
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        $this->monitor_actividad();
    }
     
   private function monitor_actividad(){
       if(strlen($this->params['url']['url'])>0){     
            loadModel('monitor_actividad');
            $monitor = new monitor_actividad();
            //debug($monitor);
            $session_data = $this->Session->read();
            unset($session_data['ErrorHandler_return']);
            unset($session_data['ERROR_SISAP_WARNING']);
            
            $modulo = 'not_session';
            if($this->Session->check('Usuario.username')){
                $url = $this->params['url']['url'];
                if($url=='modulos/index/entrada_exitosa' || $url=='modulos/index/entrada_exitosa/' || $url=='modulos/index/' || $url=='modulos/index' || $url=='modulos/' || $url=='modulos'){
                    $modulo = 'not_modulo';
                }else{
                    $modulo = str_replace('#', '', $_COOKIE['name_module']);
                }
            }
            $data_monitor=array(
                    'get_' => json_encode($_GET),
                    'post_'=> json_encode($this->data),
                    'usuario'=> $this->Session->read('Usuario.username'),
                    'session_'=> json_encode($session_data),
                    'fecha'=> date('Y-m-d H:i:s'),
                    'url_' => $this->params['url']['url'],
                    'ip'=>  $this->RequestHandler->getClientIP(),
                    'modulo'=> $modulo
            );
            $monitor->save($data_monitor);
       }
    }


    //$fixed_array = array_map("merge_arrays", array_keys($my_array), array_values($my_array));
}
?>
