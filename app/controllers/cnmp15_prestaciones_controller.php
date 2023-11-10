<?php

 class Cnmp15PrestacionesController extends AppController{

    var $name = "cnmp15_prestaciones";
    var $uses = array('cnmd15_parametro_cobro','cnmd15_bono_vaca', 'cnmd15_aguinaldo', 'cnmd15_devengado', 'cnmd15_datos_personales','ccfd04_cierre_mes', 'Cnmd01', 'v_cnmd05', 'Cnmd01');
    var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


function checkSession(){
                if (!$this->Session->check('Usuario')){
                        $this->redirect('/salir/');
                        exit();
                }else{

$this->requestAction('/usuarios/actualizar_user');
                }//fin else
}//fin checksession


    function beforeFilter(){
                    $this->checkSession();

}






function index(){

      $this->layout = "ajax";
}//fin if



 }//fin class

?>