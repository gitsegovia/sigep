<?php

 class Crcp01ActasNacimientoController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','crcd01_actas_plantillas','crcd01_actas_nacimiento');
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
    $lista = $this->crcd01_actas_plantillas->generateList($this->SQLCA()." and tipo_plantilla=6", 'titulo_tipo_acta ASC', null, '{n}.crcd01_actas_plantillas.cod_plantilla', '{n}.crcd01_actas_plantillas.titulo_tipo_acta');
	if($this->crcd01_actas_plantillas->findCount($this->SQLCA()." and tipo_plantilla=6")!=0){
		$this->concatenaN($lista, 'tipo_nomina');
		$this->set('lista_plantilla', $lista);
	}else{
		$this->set('lista_plantilla', array());
	}
}//fin index


function guardar () {
	$this->layout="ajax";
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
	if(!empty($this->params['form'])){
         if(!empty($this->params['form']['contenido_acta'])){
         	 $ano=$this->params['form']['ano_acta'];
	         $tomo=$this->params['form']['tomo'];
	         $folio=$this->params['form']['folio'];
	         $cod_acta="".$ano.'-'.$tomo.'-'.$folio;
	         $valores[]=$cod_presi;
	         $valores[]=$cod_entidad;
	         $valores[]=$cod_tipo_inst;
	         $valores[]=$cod_inst;
	         $valores[]=$cod_dep;
	         $valores[]="'".$cod_acta."'";
	         $valores[]="'".$this->params['form']['contenido_acta']."'";
	         $valores[]="'".$this->Session->read('nom_usuario')."'";
	         $valores[]="'".date('Y-m-d')."'";
	         $valores[]="'".$this->params['form']['cedula_madre']."'";
	         $valores[]="'".$this->params['form']['nombres_madre']."'";
	         $valores[]="'".$this->params['form']['cedula_padre']."'";
	         $valores[]="'".$this->params['form']['nombres_padre']."'";
	         $valores[]="'".$this->params['form']['cedula_testigo']."'";
	         $valores[]="'".$this->params['form']['nombres_testigo']."'";
	         $valores[]="'".$ano."'";
	         $valores[]="'".$tomo."'";
	         $valores[]="'".$folio."'";
	         $valores[]="'".$this->params['form']['nombre_nacido']."'";
	         $valores[]="'".$this->data['form']['sexo']."'";
             $in = $this->crcd01_actas_nacimiento->insertar($valores,$cod_acta);
            if($in>0){
                $this->set('exito','Acta registrada exitosamente');
		    }else if($in<0){
		        $this->set('error','Acta ya existe, no se puede realizar el registro');
            }else{
            	$this->set('error','No se pudo realizar el registro del acta');
            }
         }else{
         	$this->set('error','');
         }
	}else{

	}
}


function consultar () {
   $this->layout="ajax";
   $datos = $this->crcd01_actas_nacimiento->findAll($this->SQLCA(),'cod_acta, nombres_apellidos_madre,nombres_apellidos_padre,nombre_nacido', 'nombre_nacido,nombres_apellidos_madre,nombres_apellidos_padre asc');
   $this->set('datos',$datos);
}


function ver ($cod_acta) {
   $this->layout="ajax";
   $this->set('datos',$this->crcd01_actas_nacimiento->findAll($this->SQLCA()." and cod_acta='".$cod_acta."'"));
}

function ver_doc ($cod_acta) {
   $this->layout="doc";
   $this->set('datos',$this->crcd01_actas_nacimiento->findAll($this->SQLCA()." and cod_acta='".$cod_acta."'"));

}
function modificar ($cod_acta) {
   $this->layout="ajax";
   $lista = $this->crcd01_actas_plantillas->generateList($this->SQLCA()." and tipo_plantilla=6", 'titulo_tipo_acta ASC', null, '{n}.crcd01_actas_plantillas.cod_plantilla', '{n}.crcd01_actas_plantillas.titulo_tipo_acta');
	if($this->crcd01_actas_plantillas->findCount($this->SQLCA()." and tipo_plantilla=6")!=0){
		$this->concatenaN($lista, 'tipo_nomina');
		$this->set('lista_plantilla', $lista);
	}else{
		$this->set('lista_plantilla', array());
	}
   $this->set('datos',$this->crcd01_actas_nacimiento->findAll($this->SQLCA()." and cod_acta='".$cod_acta."'"));
}

function guardar_modificar () {
	$this->layout="ajax";
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
	if(!empty($this->params['form'])){
         if(!empty($this->params['form']['contenido_acta'])){
         	 $ano=$this->params['form']['ano_acta'];
	         $tomo=$this->params['form']['tomo'];
	         $folio=$this->params['form']['folio'];
	         $cod_acta="".$ano.'-'.$tomo.'-'.$folio;
	         $valores[]=" contenido_acta='".$this->params['form']['contenido_acta']."'";
	         $valores[]=" cedula_madre='".$this->params['form']['cedula_madre']."'";
	         $valores[]=" nombres_apellidos_madre='".$this->params['form']['nombres_madre']."'";
	         $valores[]=" cedula_padre='".$this->params['form']['cedula_padre']."'";
	         $valores[]=" nombres_apellidos_padre='".$this->params['form']['nombres_padre']."'";
	         $valores[]=" cedula_testigo='".$this->params['form']['cedula_testigo']."'";
	         $valores[]=" nombres_apellidos_testigo='".$this->params['form']['nombres_testigo']."'";
	         $valores[]=" nombre_nacido='".$this->params['form']['nombre_nacido']."'";
	         $valores[]=" sexo='".$this->data['form']['sexo']."'";

             $in = $this->crcd01_actas_nacimiento->modificar($valores,$cod_acta);
            if($in>0){
                $this->set('exito','Acta modificada exitosamente');
		    }else{
            	$this->set('error','No se pudo realizar la modificación del acta');
            }
         }else{
         	$this->set('error','');
         }
	}else{

	}
}


function eliminar ($cod_acta) {
   $this->layout="ajax";
   $r=$this->crcd01_actas_nacimiento->execute("DELETE FROM crcd01_actas_nacimiento WHERE ".$this->SQLCA()." and cod_acta='".$cod_acta."'");
   if($r>0){
   	$this->set('exito','Acta eliminada exitosamente');
   }else{
	$this->set('error','No se pudo eliminar el acta');
   }
   $this->render('vacio');
}


function cargar_plantilla ($cod_plantilla) {
   $this->layout="ajax";
   $this->set('datos',$this->crcd01_actas_plantillas->findAll($this->SQLCA()." and cod_plantilla=".$cod_plantilla));
}


}//fin class
?>
