<?php

 class Crcp01ActasPlantillasController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','crcd01_actas_plantillas');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form','Fck');


function checkSession(){
	if (!$this->Session->check('Usuario')){
			$this->redirect('/salir/');
			exit();
	}else{
		$this->requestAction('/usuarios/actualizar_user');
	}
}//fin checksession

function beforeFilter(){
	$this->checkSession();
	if($this->ano_ejecucion()!=""){
		return;
	}else{
		echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
		exit();
	}
}

function verifica_SS($i){
			/**
			 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
			 * para ser insertados en todas las tablas.
			 * */
			switch ($i){
				case 1:return $this->Session->read('SScodpresi');break;
				case 2:return $this->Session->read('SScodentidad');break;
				case 3:return $this->Session->read('SScodtipoinst');break;
				case 4:return $this->Session->read('SScodinst');break;
				case 5:return $this->Session->read('SScoddep');break;
				case 6:return $this->Session->read('entidad_federal');break;
				default:
					 return "NULO";
			}//fin switch
}//fin verifica_SS

function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }
         return $sql_re;
}//fin funcion SQLCA

function index () {
    $this->layout="ajax";

}//fin index


function guardar () {
	$this->layout="ajax";
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
	if(!empty($this->params['form'])){
         $titulo=up($this->params['form']['titulo_tipo_acta']);
         $contenido=$this->params['form']['contenido_acta'];
         $tipo_plantilla=$this->data['form']['tipo_plantilla'];
         if(!empty($titulo) && !empty($contenido)){
            $insert="INSERT INTO crcd01_actas_plantillas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,contenido_acta, titulo_tipo_acta,tipo_plantilla) VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep,'$contenido', '$titulo',$tipo_plantilla);";
            $in = $this->crcd01_actas_plantillas->execute($insert);
            if($in>0){
                $this->set('exito','Plantilla registrada exitosamente');

		    }else{
		        $this->set('error','No se pudo realizar el registro de la plantilla');
            }
         }else{
         	$this->set('error','');
         }
	}else{

	}
}


function consultar () {
   $this->layout="ajax";
   $datos = $this->crcd01_actas_plantillas->findAll($this->SQLCA(),'cod_plantilla,titulo_tipo_acta', 'titulo_tipo_acta asc');
   $this->set('datos',$datos);
}


function ver ($cod_plantilla) {
   $this->layout="ajax";
   $this->set('datos',$this->crcd01_actas_plantillas->findAll($this->SQLCA()." and cod_plantilla=".$cod_plantilla));
}

function modificar ($cod_plantilla) {
   $this->layout="ajax";
   $this->set('datos',$this->crcd01_actas_plantillas->findAll($this->SQLCA()." and cod_plantilla=".$cod_plantilla));
}

function guardar_modificar () {
	$this->layout="ajax";
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
	if(!empty($this->params['form'])){
		 $cod_plantilla=$this->params['form']['cod_plantilla'];
         $titulo=up($this->params['form']['titulo_tipo_acta']);
         $contenido=$this->params['form']['contenido_acta'];
         $tipo_plantilla=$this->data['form']['tipo_plantilla'];
         $control=$this->params['form']['control'];
         if(!empty($titulo) && !empty($contenido) && $control>0){
            $insert="UPDATE crcd01_actas_plantillas SET contenido_acta='$contenido', titulo_tipo_acta='$titulo' , tipo_plantilla=$tipo_plantilla WHERE ".$this->SQLCA()." and cod_plantilla=".$cod_plantilla;
            $in = $this->crcd01_actas_plantillas->execute($insert);
            if($in>0){
                $this->set('exito','Plantilla modificada exitosamente');

		    }else{
		        $this->set('error','No se pudo realizar la modificación a la plantilla');
            }
         }else{
         	$this->set('error','');
         }
	}else{

	}
}


function eliminar ($cod_plantilla) {
   $this->layout="ajax";
   $r=$this->crcd01_actas_plantillas->execute("DELETE FROM crcd01_actas_plantillas WHERE ".$this->SQLCA()." and cod_plantilla=".$cod_plantilla);
   if($r>0){
   	$this->set('exito','Plantilla eliminada exitosamente');
   }else{
	$this->set('error','No se pudo eliminar la plantilla');
   }
   $this->render('vacio');
}


}//fin class
?>
