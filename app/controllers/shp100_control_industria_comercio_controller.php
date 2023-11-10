<?php

 class Shp100ControlIndustriaComercioController extends AppController{

	var $name = 'shp100_control_industria_comercio';
	var $uses = array('shd100_control_industria_comercio');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');



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

 }




function index() {

   	$this->layout = "ajax";

$datos_filas_aux = $this->shd100_control_industria_comercio->findAll($this->condicion());

$utiliza_planillas_liquidacion_previa = !isset($datos_filas_aux[0]["shd100_control_industria_comercio"]["utiliza_planillas_liquidacio"])?"":$datos_filas_aux[0]["shd100_control_industria_comercio"]["utiliza_planillas_liquidacio"];
$frecuencia_pago_segun_ordenanza      = !isset($datos_filas_aux[0]["shd100_control_industria_comercio"]["frecuencia_pago_segun_ordena"])?"":$datos_filas_aux[0]["shd100_control_industria_comercio"]["frecuencia_pago_segun_ordena"];
$this->set("opc_1", $utiliza_planillas_liquidacion_previa);
$this->set("opc_2", $frecuencia_pago_segun_ordenanza);

}





function guardar(){
$this->layout = "ajax";
$cod_presi                =       $this->Session->read('SScodpresi');
$cod_entidad              =       $this->Session->read('SScodentidad');
$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
$cod_inst                 =       $this->Session->read('SScodinst');
$cod_dep                  =       $this->Session->read('SScoddep');

if(!empty($this->data["shp100_control_industria_comercio"]["tipo_planilla"])){
	if(!empty($this->data["shp100_control_industria_comercio"]["tipo_frecuencia"])){

$Tfilas=$this->shd100_control_industria_comercio->findCount($this->condicion());

if($Tfilas==0){
$campos = "   cod_presi,
			  cod_entidad,
			  cod_tipo_inst,
			  cod_inst,
			  cod_dep,
			  utiliza_planillas_liquidacion_previa,
			  frecuencia_pago_segun_ordenanza ";

$VALUES = "   '".$cod_presi."',
			  '".$cod_entidad."',
			  '".$cod_tipo_inst."',
			  '".$cod_inst."',
			  '".$cod_dep."',
			  '".$this->data["shp100_control_industria_comercio"]["tipo_planilla"]."',
			  '".$this->data["shp100_control_industria_comercio"]["tipo_frecuencia"]."' ";
$sw = $this->shd100_control_industria_comercio->execute("BEGIN; INSERT INTO shd100_control_industria_comercio (".$campos.") VALUES (".$VALUES.");");
}else{
$VALUES = "   utiliza_planillas_liquidacion_previa = '".$this->data["shp100_control_industria_comercio"]["tipo_planilla"]."',
			  frecuencia_pago_segun_ordenanza      = '".$this->data["shp100_control_industria_comercio"]["tipo_frecuencia"]."'";
$sw = $this->shd100_control_industria_comercio->execute("BEGIN; update shd100_control_industria_comercio set ".$VALUES." WHERE ".$this->condicion()."; ");
}//fin else

	if($sw>1){
	  $_SESSION["utiliza_planillas_liquidacion_previa"] = $this->data["shp100_control_industria_comercio"]["tipo_planilla"];
      $_SESSION["frecuencia_pago_segun_ordenanza"]      = $this->data["shp100_control_industria_comercio"]["tipo_frecuencia"];
	  $this->shd100_control_industria_comercio->execute("COMMIT;");
	  $this->data=null;
	  $this->set('Message_existe', 'EL Registro fue guardado con exito');
	  $this->set('editar', 'si');

	}else{
	  $this->shd100_control_industria_comercio->execute("ROLLBACK;");
	  $this->set('errorMessage', 'Disculpe, El Registro no fue guardado');
	}//fin else


}else{$this->set('errorMessage', 'Inserte la frecuencia de pago');}
}else{$this->set('errorMessage', 'Inserte si El cobro se realiza mediante las Planillas de Liquidación previa');}






}





function editar(){
	$this->layout = "ajax";
}



}//FIN DEL CONTROLADOR
?>
