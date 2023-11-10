<?php

class VentanaPdfReporteAjaxController extends AppController {

   var $name = "ventana_pdf_reporte_ajax";
   var $uses = array();
   var $helpers = array('Html','Ajax','Javascript','Sisap');














function ventana_formularios_barra_proceso($opcion=null, $name=null, $automatico=null){

$this->layout = "ajax";

$this->set("name",       $name);
$this->set("automatico", $automatico);
$this->set("var",        $opcion);

$this->render("index");

}//fin function





function actualiza_porcentaje_reporte(){

$this->layout = "ajax";

}//fin function





}//fin class



?>