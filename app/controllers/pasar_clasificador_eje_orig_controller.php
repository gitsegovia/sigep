<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class PasarClasificadorEjeOrigController extends AppController {
   var $name = 'pasar_clasificador_eje_orig';
   var $uses = array('cfpd05','cfpd09_metas_proyecto','cfpd09_metas_actividad','cfpd09_metas_subprog','cfpd09_metas_programa',
                     'cfpd09_metas_sector','cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto',
                     'cfpd02_activ_obra', 'cfpd01_formulacion', 'arrd05','cprogramatica', 'v_clasificador_partidas_ejercicio',
                     'cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec',
                     'cfpd01_ano_auxiliar', 'cfpd01_grupo', 'cfpd01_partida', 'cfpd01_generica', 'cfpd01_especifica', 'cfpd01_sub_espec',
                     'cfpd01_auxiliar', 'cfpd01_formulacion','clasificador','ccfd04_cierre_mes');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf');

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
					//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession

 function beforeFilter(){
 	$this->checkSession();
	/*echo'<script>' .
							'document.getElementById("valida_codigo").innerHTML = "";' .
							'document.getElementById("valida_codigo").style.display = "none";' .
							'</script>';*/
	$cod_dep = $this->Session->read('SScoddep');
 }



function index(){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

    $this->set('year',$this->ano_ejecucion());


 }//fin function












function pasar_de_ejercicio_original(){


$this->layout = "ajax";


		$this->cfpd02_sector->execute("select funcion_actualizar_clasificador_original('".$this->data["pasar_clasificador_eje_orig"]["ano"]."');");


$this->set('Message_existe', "Datos actualizados con exito");
$this->index();
$this->render("index");


}//fin function















}//fin class
?>
