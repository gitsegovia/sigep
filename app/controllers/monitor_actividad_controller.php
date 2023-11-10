<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of monitor_actividad_controller
 *
 * @author jose
 * @property monitor_actividad $monitor_actividad 
 */
class MonitorActividadController extends AppController{
    var $uses = array('monitor_actividad');
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
        
    }
    
    public function consulta($pagina = 1){
        $this->autoRender=false;
        $data = $this->data;
        if(!empty($data)){
            switch ($data['Consulta']['tipo']){
                case 1://Busqueda por Usuario
                        echo json_encode($this->consultaUsuario($data,$pagina));
                    break;
                case 2://Busqueda por Modulo
                        echo json_encode($this->consultaModulo($data,$pagina));
                    break;
                    case 3://Consulta Avanzada
                        echo json_encode($this->consultaAvanzado($data,$pagina));
                    break;
                default ://Busqueda Avanzada
                       // echo json_encode($this->consultaAvanzado($data,$pagina));
                    break;
            }
        }else{
            echo json_encode(array('completed'=>0,'message'=>'No completado.'));
        }
    }
    
    /**
     * Busqueda por usuario, recibe lo que viene del post data y la pagina que se desea mostrar
     */
    private function consultaUsuario($data,$pagina=1){
        //Sino existe avanzado
        $limit = 100;
        if(!isset($data['Consulta']['avanzado'])){
            $param = $data['Consulta']['parametro'];
            $sql="SELECT count(*) as peticiones FROM monitor_actividad WHERE usuario='$param'";
            $cantidad = $this->monitor_actividad->query($sql);
            
            $rs = $this->monitor_actividad->findAll(array('usuario'=>$param), 'id, url_, usuario, ip, fecha, get_, post_, session_, modulo', null, $limit,$pagina);
            foreach ($rs as $key => $value) {
                $rs[$key]['monitor_actividad']['id']=str_pad($rs[$key]['monitor_actividad']['id'], 12, "0", STR_PAD_LEFT);
                $date = new DateTime($rs[$key]['monitor_actividad']['fecha']);
                $rs[$key]['monitor_actividad']['fecha'] = $date->format('Y-m-d h:i:s A');
                $rs[$key]['monitor_actividad']['session_'] = json_decode($rs[$key]['monitor_actividad']['session_']);
                if($rs[$key]['monitor_actividad']['get_']=='null'){
                   $rs[$key]['monitor_actividad']['get_']=0; 
                }else{
                   $rs[$key]['monitor_actividad']['get_']=1; 
                }
                if($rs[$key]['monitor_actividad']['post_']=='null'){
                   $rs[$key]['monitor_actividad']['post_']=0; 
                }else{
                   $rs[$key]['monitor_actividad']['post_']=1; 
                }
            }
            
            
            return array('completed'=>'1',
                         'message'=>'Consulta Completada', 
                         'query'=>$data, 
                         'data'=>$rs, 
                         'peticiones'=>$cantidad[0][0]['peticiones'],
                         'paginas'=>  ceil($cantidad[0][0]['peticiones']/$limit),
                         'pag_actual'=>$pagina,
                         'modo'=>'usuario',
                        );
        }else{
            //En base a la solicitud GET u POST consulto
            $param = $data['Consulta']['parametro'];
            $param_l = strtolower($data['Consulta']['parametro']);
            $param_u = strtoupper($data['Consulta']['parametro']);
            
            $condicion="(usuario='$param' OR usuario='$param_l' OR usuario='$param_u')";
            //Verficamos que peticion desea
            if($data['Consulta']['peticion']=='get_' || $data['Consulta']['peticion']=='GET_'){
                $condicion.= " AND post_='null'";
            }elseif($data['Consulta']['peticion']=='post_' || $data['Consulta']['peticion']=='POST_'){
                $condicion.= " AND post_!='null' ";
            }elseif($data['Consulta']['peticion']=='get_post' || $data['Consulta']['peticion']=='POST_GET'){
                $condicion.= " ";
            }
            
            //Ahora buscamos que existan los valores de la consulta
            if(strlen($data['Consulta']['consulta'])>0){
               
               $split_ = split(';', $data['Consulta']['consulta']);
               $busq="";
                
                foreach ($split_ as $key => $value) {
                   $aux = false;
                   if(strlen($value)>0){
                        $v = $value;
                        $v_l = strtolower($value);
                        $v_U = strtoupper($value);
                        $busq.= " url_ LIKE '%$v%' OR url_ LIKE '%$v_l%' OR url_ LIKE '%$v_U%'";
                        $busq.= " OR get_ LIKE '%$v%' OR get_ LIKE '%$v_l%' OR get_ LIKE '%$v_U%'";
                        $busq.= " OR post_ LIKE '%$v%' OR post_ LIKE '%$v_l%' OR post_ LIKE '%$v_U%'";
                   }
                   
                   if(($key+1<sizeof($split_)) && $aux && isset($split_[$key+1]) && strlen($split_[$key+1])>0)
                        $busq.= " OR ";
               }
               $condicion.="AND ($busq)";
            }
            
            if(strlen($data['Consulta']['f_inicio'])==10 && strlen($data['Consulta']['f_fin'])==10){
                //Agregamos a la condicion el rango de fechas
                $f_inicio = str_replace('/', '-',$data['Consulta']['f_inicio'])." 00:00:00";
                $f_fin = str_replace('/', '-',$data['Consulta']['f_fin'])." 23:59:59";
                $condicion.=" AND (fecha BETWEEN timestamp '$f_inicio' AND timestamp '$f_fin')";
            }
            $orden = strtoupper($data['Consulta']['orden']);
            $sql="SELECT count(*) as peticiones FROM monitor_actividad WHERE $condicion ";
            $cantidad = $this->monitor_actividad->query($sql);
            
            $rs = $this->monitor_actividad->findAll($condicion, 'id, url_, usuario, ip, fecha, get_, post_, session_, modulo', "fecha $orden", $limit,$pagina);
            foreach ($rs as $key => $value) {
                $rs[$key]['monitor_actividad']['id']=str_pad($rs[$key]['monitor_actividad']['id'], 12, "0", STR_PAD_LEFT);
                $date = new DateTime($rs[$key]['monitor_actividad']['fecha']);
                $rs[$key]['monitor_actividad']['fecha'] = $date->format('Y-m-d h:i:s A');
                $rs[$key]['monitor_actividad']['session_'] = json_decode($rs[$key]['monitor_actividad']['session_']);
                if($rs[$key]['monitor_actividad']['get_']=='null'){
                   $rs[$key]['monitor_actividad']['get_']=0; 
                }else{
                   $rs[$key]['monitor_actividad']['get_']=1; 
                }
                if($rs[$key]['monitor_actividad']['post_']=='null'){
                   $rs[$key]['monitor_actividad']['post_']=0; 
                }else{
                   $rs[$key]['monitor_actividad']['post_']=1; 
                }
            }
            return array('completed'=>'1',
                         'message'=>'Consulta Completada', 
                         'query'=>$data, 
                         'data'=>$rs, 
                         'peticiones'=>$cantidad[0][0]['peticiones'],
                         'paginas'=>  ceil($cantidad[0][0]['peticiones']/$limit),
                         'pag_actual'=>$pagina, 
                         'modo'=>'usuario',
                         //'sql'=>$sql
                    );
        }
    }
    
    
    private function consultaModulo($data,$pagina=1){
        //Sino existe avanzado
        $limit = 100;
        if(!isset($data['Consulta']['avanzado'])){
            $param_l = strtolower($data['Consulta']['parametro']);
            $param_U = strtoupper($data['Consulta']['parametro']);
            $param = ($data['Consulta']['parametro']);
            //$sql="SELECT count(*) as peticiones FROM monitor_actividad WHERE url_ LIKE '%$param%' OR url_ LIKE '%$param_l%' OR url_ LIKE '%$param_U%' OR session_ LIKE '%$param%' OR session_ LIKE '%$param_l%' OR session_ LIKE '%$param_U%'";
            $sql="SELECT count(*) as peticiones FROM monitor_actividad WHERE modulo LIKE '%$param%' OR modulo LIKE '%$param_l%' OR modulo LIKE '%$param_U%'; ";
            $cantidad = $this->monitor_actividad->query($sql);
            
            //$rs = $this->monitor_actividad->findAll("url_ LIKE '%$param%' OR url_ LIKE '%$param_l%' OR url_ LIKE '%$param_U%' OR session_ LIKE '%$param%' OR session_ LIKE '%$param_l%' OR session_ LIKE '%$param_U%'", 'id, url_, usuario, ip, fecha, get_, post_, session_, modulo', null, $limit,$pagina);
            $rs = $this->monitor_actividad->findAll("modulo LIKE '%$param%' OR modulo LIKE '%$param_l%' OR modulo LIKE '%$param_U%'", 'id, url_, usuario, ip, fecha, get_, post_, session_, modulo', null, $limit,$pagina);
            foreach ($rs as $key => $value) {
                $rs[$key]['monitor_actividad']['id']=str_pad($rs[$key]['monitor_actividad']['id'], 12, "0", STR_PAD_LEFT);
                $date = new DateTime($rs[$key]['monitor_actividad']['fecha']);
                $rs[$key]['monitor_actividad']['fecha'] = $date->format('Y-m-d h:i:s A');
                $rs[$key]['monitor_actividad']['session_'] = json_decode($rs[$key]['monitor_actividad']['session_']);
                if($rs[$key]['monitor_actividad']['get_']=='null'){
                   $rs[$key]['monitor_actividad']['get_']=0; 
                }else{
                   $rs[$key]['monitor_actividad']['get_']=1; 
                }
                if($rs[$key]['monitor_actividad']['post_']=='null'){
                   $rs[$key]['monitor_actividad']['post_']=0; 
                }else{
                   $rs[$key]['monitor_actividad']['post_']=1; 
                }
            }
            return array('completed'=>'1',
                         'message'=>'Consulta Completada', 
                         'query'=>$data, 
                         'data'=>$rs, 
                         'peticiones'=>$cantidad[0][0]['peticiones'],
                         'paginas'=>  ceil($cantidad[0][0]['peticiones']/$limit),
                         'pag_actual'=>$pagina,
                         'modo'=>'modulo',
                         //'sql'=>$sql
                        );
        }else{
            //En base a la solicitud GET u POST consulto
            $param = $data['Consulta']['parametro'];
            $param_l = strtolower($data['Consulta']['parametro']);
            $param_u = strtoupper($data['Consulta']['parametro']);
            
            $condicion="(modulo LIKE '%$param%' OR modulo LIKE '%$param_l%' OR modulo LIKE '%$param_u%') ";
            //$condicion="(url_ LIKE '%$param%' OR url_ LIKE '%$param_l%' OR url_ LIKE '%$param_u%' OR session_ LIKE '%$param%' OR session_ LIKE '%$param_l%' OR session_ LIKE '%$param_u%')";
            //$condicion="(url_ LIKE '%$param%' OR url_ LIKE '%$param_l%' OR url_ LIKE '%$param_u%')";
            //$condicion="(session_ LIKE '%$param%' OR session_ LIKE '%$param_l%' OR session_ LIKE '%$param_U%')";
            //Verficamos que peticion desea
            if($data['Consulta']['peticion']=='get_' || $data['Consulta']['peticion']=='GET_'){
                $condicion.= " AND post_='null' ";
            }elseif($data['Consulta']['peticion']=='post_' || $data['Consulta']['peticion']=='POST_'){
                $condicion.= " AND post_!='null' ";
            }elseif($data['Consulta']['peticion']=='get_post' || $data['Consulta']['peticion']=='POST_GET'){
                $condicion.= " ";
            }
            
            //Ahora buscamos que existan los valores de la consulta
            if(strlen($data['Consulta']['consulta'])>0){
               
               $split_ = split(';', $data['Consulta']['consulta']);
               $busq="";
               
               foreach ($split_ as $key => $value) {
                   $aux = false;
                   if(strlen($value)>0){
                    $v = $value;
                    $v_l = strtolower($value);
                    $v_U = strtoupper($value);
                    $busq.= " url_ LIKE '%$v%' OR url_ LIKE '%$v_l%' OR url_ LIKE '%$v_U%' ";
                    $busq.= " OR usuario LIKE '%$v%' OR usuario LIKE '%$v_l%' OR usuario LIKE '%$v_U%' ";
                    $busq.= " OR get_ LIKE '%$v%' OR get_ LIKE '%$v_l%' OR get_ LIKE '%$v_U%' ";
                    $busq.= " OR post_ LIKE '%$v%' OR post_ LIKE '%$v_l%' OR post_ LIKE '%$v_U%' ";
                    $busq.= " OR session_ LIKE '%$v%' OR session_ LIKE '%$v_l%' OR session_ LIKE '%$v_U%' ";
                    $aux = true;
                   }
                    if(($key+1<sizeof($split_)) && $aux && isset($split_[$key+1]) && strlen($split_[$key+1])>0)
                        $busq.= " OR ";
               }
               $condicion.="AND ($busq)";
            }
            
            if(strlen($data['Consulta']['f_inicio'])==10 && strlen($data['Consulta']['f_fin'])==10){
                //Agregamos a la condicion el rango de fechas
                $f_inicio = str_replace('/', '-',$data['Consulta']['f_inicio'])." 00:00:00";
                $f_fin = str_replace('/', '-',$data['Consulta']['f_fin'])." 23:59:59";
                $condicion.=" AND (fecha BETWEEN timestamp '$f_inicio' AND timestamp '$f_fin')";
            }
            
            $orden = strtoupper($data['Consulta']['orden']);
            $sql="SELECT count(*) as peticiones FROM monitor_actividad WHERE $condicion ";
            $cantidad = $this->monitor_actividad->query($sql);
            
            $rs = $this->monitor_actividad->findAll($condicion, 'id, url_, usuario, ip, fecha, get_, post_, session_, modulo', "fecha $orden", $limit,$pagina);
            foreach ($rs as $key => $value) {
                $rs[$key]['monitor_actividad']['id']=str_pad($rs[$key]['monitor_actividad']['id'], 12, "0", STR_PAD_LEFT);
                $date = new DateTime($rs[$key]['monitor_actividad']['fecha']);
                $rs[$key]['monitor_actividad']['fecha'] = $date->format('Y-m-d h:i:s A');
                $rs[$key]['monitor_actividad']['session_'] = json_decode($rs[$key]['monitor_actividad']['session_']);
                if($rs[$key]['monitor_actividad']['get_']=='null'){
                   $rs[$key]['monitor_actividad']['get_']=0; 
                }else{
                   $rs[$key]['monitor_actividad']['get_']=1; 
                }
                if($rs[$key]['monitor_actividad']['post_']=='null'){
                   $rs[$key]['monitor_actividad']['post_']=0; 
                }else{
                   $rs[$key]['monitor_actividad']['post_']=1; 
                }
            }
            return array('completed'=>'1',
                         'message'=>'Consulta Completada', 
                         'query'=>$data, 
                         'data'=>$rs, 
                         'peticiones'=>$cantidad[0][0]['peticiones'],
                         'paginas'=>  ceil($cantidad[0][0]['peticiones']/$limit),
                         'pag_actual'=>$pagina, 
                         'modo'=>'modulo',
                         //'sql'=>$sql
                         );
        }
    }
    
    
    private function consultaAvanzado($data,$pagina=1) {
            $limit = 100;
        //realizamos una consutal avanzada, que se compone en base a lo que viene en data
            $param_l = strtolower($data['Consulta']['parametro']);
            $param_U = strtoupper($data['Consulta']['parametro']);
            $param = ($data['Consulta']['parametro']);
            
            $condicion = "";
            $condiciones_add = 0;
            if(isset($data['Consulta']['get_'])){//Si se esta pidiendo GET
                $condicion.=" get_ LIKE '%$param%' OR get_ LIKE '%$param_l%' OR get_ LIKE '%$param_U%' ";
                $condiciones_add++;
            }
            if(isset($data['Consulta']['post_'])){//Si Se esta pidiendo POST
                if($condiciones_add==1){
                    $condicion.=" OR post_ LIKE '%$param%' OR post_ LIKE '%$param_l%' OR post_ LIKE '%$param_U%' ";
                }else{
                    $condicion.=" post_ LIKE '%$param%' OR post_ LIKE '%$param_l%' OR post_ LIKE '%$param_U%' "; 
                }
                //SI Solamente se esta pidiendo POST y no GET
                if(!isset($data['Consulta']['get_'])){
                    $condicion=" post_!='null' AND (post_ LIKE '%$param%' OR post_ LIKE '%$param_l%' OR post_ LIKE '%$param_U%') "; 
                }
                $condiciones_add++;
            }elseif(isset($data['Consulta']['get_'])){//Verifico que solo es GET
                $condicion=" post_='null' AND (get_ LIKE '%$param%' OR get_ LIKE '%$param_l%' OR get_ LIKE '%$param_U%') "; 
            }
            
            if(strlen($condicion)==0 && strlen($param)==0){
                $condicion = " TRUE ";
            }elseif(strlen($condicion)==0 && strlen($param)>0){
               $condicion = "";
               $split_ = split(' ', $param);
               foreach ($split_ as $key => $value) {
                $v = $value;
                $v_l = strtolower($value);
                $v_U = strtoupper($value);
                    $condicion.= " url_ LIKE '%$v%' OR url_ LIKE '%$v_l%' OR url_ LIKE '%$v_U%'";
                    $condicion.= " OR get_ LIKE '%$v%' OR get_ LIKE '%$v_l%' OR get_ LIKE '%$v_U%'";
                    $condicion.= " OR post_ LIKE '%$v%' OR post_ LIKE '%$v_l%' OR post_ LIKE '%$v_U%'";
                    $condicion.= " OR session_ LIKE '%$v%' OR session_ LIKE '%$v_l%' OR session_ LIKE '%$v_U%'";
                    $condicion.= " OR usuario LIKE '%$v%' OR usuario LIKE '%$v_l%' OR usuario LIKE '%$v_U%'";
                    if($key+1<sizeof($split_))
                        $condicion.= " OR ";
               }
               
            }
            $sql="SELECT count(*) as peticiones FROM monitor_actividad WHERE $condicion";
            $cantidad = $this->monitor_actividad->query($sql);
            
            $rs = $this->monitor_actividad->findAll($condicion, 'id, url_, usuario, ip, fecha, get_, post_', null, $limit,$pagina);
            foreach ($rs as $key => $value) {
                $rs[$key]['monitor_actividad']['id']=str_pad($rs[$key]['monitor_actividad']['id'], 12, "0", STR_PAD_LEFT);
                $date = new DateTime($rs[$key]['monitor_actividad']['fecha']);
                $rs[$key]['monitor_actividad']['fecha'] = $date->format('Y-m-d h:i:s A');
                if($rs[$key]['monitor_actividad']['get_']=='null'){
                   $rs[$key]['monitor_actividad']['get_']=0; 
                }else{
                   $rs[$key]['monitor_actividad']['get_']=1; 
                }
                if($rs[$key]['monitor_actividad']['post_']=='null'){
                   $rs[$key]['monitor_actividad']['post_']=0; 
                }else{
                   $rs[$key]['monitor_actividad']['post_']=1; 
                }
            }
            return array('completed'=>'1',
                         'message'=>'Consulta Completada', 
                         'query'=>$data, 
                         'data'=>$rs, 
                         'peticiones'=>$cantidad[0][0]['peticiones'],
                         'paginas'=>  ceil($cantidad[0][0]['peticiones']/$limit),
                         'pag_actual'=>$pagina,
                         'modo'=>'avanzado',
                         //'sql'=>$sql
                        );
                         
        
    }
    
    
    public function ver_peticion() {
        $this->autoRender = FALSE;
        $rs = $this->monitor_actividad->query("SELECT * FROM monitor_actividad WHERE id='".((int)  $this->data['id'])."'");
        
        $date = new DateTime($rs[0][0]['fecha']);
        
        //$session_ = json_decode($rs[0][0]['session_']);
        
        $data = array(
            'id'=>str_pad($rs[0][0]['id'], 12, "0", STR_PAD_LEFT),
            'fecha'=>$date->format('Y-m-d h:i:s A'),
            'usuario'=>$rs[0][0]['usuario'],
            'ip'=>$rs[0][0]['ip'],
            'url'=>$rs[0][0]['url_'],
            'modulo'=>$rs[0][0]['modulo'],//$session_->Modulo.' - '.$session_->Modulo2,
            'session'=>  json_decode($rs[0][0]['session_']),
            'get'=>  json_decode($rs[0][0]['get_']),
            'post'=>  json_decode($rs[0][0]['post_'])
            );
        echo json_encode(array('data'=>$data,'message'=>'Datos Cargados','completed'=>1));
    }
}
