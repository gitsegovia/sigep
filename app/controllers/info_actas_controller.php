<?php

class InfoActasController extends AppController
{
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap','Infogob');
    var $uses = array('cugd_usuarios','crcd01_actas_defuncion','crcd01_actas_matrimonio','crcd01_actas_nacimiento');

	function checkSession(){
		if (!$this->Session->check('infogobierno')){
				$this->redirect('/infogobierno/salir_todo');
				exit();
		}
	}//fin checksession


	function beforeFilter(){
		$this->checkSession();
	}


	function index(){
	    	$this->layout = "ajax";
	    	$msj=isset($_SESSION['msj'])?$_SESSION['msj']:'';
	    	$this->set('msj',$msj);
	    	$_SESSION['msj']="";
            $tipo_plantillas = array('3'=>'ACTA DE DEFUNCIÓN','5'=>'ACTA DE MATRIMONIO','6'=>'ACTA DE NACIMIENTO');
            $this->set('tipo_actas',$tipo_plantillas);
	    	$this->set('cedula_identidad',$_SESSION['infogobierno']['cedula_identidad']);
	}//fin

    function mostrar_buscar () {
		 $this->layout="ajax";
		 if(isset($this->data['info_acta'])){
		 	if(!empty($this->data['info_acta']['tipo_actas']) && (!empty($this->data['info_acta']['cedula_madre_padre']) || !empty($this->data['info_acta']['cedula_difunto']) || !empty($this->data['info_acta']['cedula_novia_novio']))){
                 if($this->data['info_acta']['tipo_actas']==3){
              	     $modelo ="crcd01_actas_defuncion";
              	     $campos ="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_acta,nombres_apellidos_difunto,nombres_apellidos_exponente";
              	     $condicion = " cedula_difunto='".$this->data['info_acta']['cedula_difunto']."'";
                 }else if($this->data['info_acta']['tipo_actas']==5){
                     $modelo ="crcd01_actas_matrimonio";
              	     $campos ="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_acta,nombres_apellidos_novia,nombres_apellidos_novio";
              	     $condicion = " cedula_novia='".$this->data['info_acta']['cedula_novia_novio']."' OR  cedula_novio='".$this->data['info_acta']['cedula_novia_novio']."'";

                 }else if($this->data['info_acta']['tipo_actas']==6){
                     $modelo ="crcd01_actas_nacimiento";
              	     $campos ="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_acta,nombre_nacido,nombres_apellidos_madre,nombres_apellidos_padre";
                     $condicion = " cedula_madre='".$this->data['info_acta']['cedula_madre_padre']."' OR  cedula_padre='".$this->data['info_acta']['cedula_madre_padre']."'";
                 }
                   $this->set('tipo',$this->data['info_acta']['tipo_actas']);
                   $this->set('modelo',$modelo);
                   $this->set('datos',$this->$modelo->findAll($condicion,$campos,'cod_acta ASC'));
                   $c=$this->$modelo->findCount($condicion);
                   if($c==0){
                   	  $this->set('msj',array('Acta no encontrada','error'));
                   }
		 	}
		 }else{
		 }

	}

	function ver_pdf ($tipo=null,$cod_presi=null,$cod_entidad=null,$cod_tipo_inst=null,$cod_inst=null,$cod_dep=null,$cod_acta=null) {
	   $this->layout="pdf";
	if(isset($tipo) && isset($cod_presi) && isset($cod_entidad) && isset($cod_tipo_inst) && isset($cod_inst) && isset($cod_dep) && isset($cod_acta)){
          if($tipo==3){
              	     $modelo ="crcd01_actas_defuncion";
          }else if($tipo==5){
                     $modelo ="crcd01_actas_matrimonio";
          }else if($tipo==6){
                     $modelo ="crcd01_actas_nacimiento";
          }
          $this->set('modelo',$modelo);
          $this->set('datos',$this->$modelo->findAll("cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_acta='$cod_acta'",null,'cod_acta ASC'));
		}
	}



}//fin class
?>