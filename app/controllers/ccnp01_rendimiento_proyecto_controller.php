<?php





 class ccnp01RendimientoProyectoController extends AppController {
    var $name    = 'ccnp01_rendimiento_proyecto';
	var $uses    = array('ccnd01_tipo_directivo','cugd01_centropoblados','ccnd02_proyectos');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');





function checkSession(){
				if (!$this->Session->check('concejo_comunal')){
						$this->redirect('/salir');
						exit();
				}
}//fin checksession





 function beforeFilter(){
 	$this->checkSession();

 }




function index(){
	$this->layout="ajax";
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');


	$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";
	$conditions2  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro;

	$ver=$this->cugd01_centropoblados->execute("select poblacion from cugd01_centros_poblados where ".$conditions2);
	$ver2=$this->ccnd02_proyectos->execute("select costo_proyecto from ccnd02_proyectos where ".$conditions);
	if($ver[0][0]['poblacion']!=0){
		$rendimiento=($ver2[0][0]['costo_proyecto']/$ver[0][0]['poblacion']);
	}else{
		$rendimiento=0;
	}


	$this->set('poblacion',$ver[0][0]['poblacion']);
	$this->set('costo_proyecto',$ver2[0][0]['costo_proyecto']);
	$this->set('rendimiento',$rendimiento);

}//index



}//fin function

?>